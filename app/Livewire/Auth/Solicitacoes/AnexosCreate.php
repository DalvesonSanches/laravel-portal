<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\Attributes\On; // Importante para o listener do modal O atributo #[On] serve exclusivamente para ouvir (escutar) eventos
use App\Models\SolicitacaosAnexos;
use App\Models\Solicitacao;
use App\Models\Ocorrencias;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use App\Services\MinioStorageService; //service do minion
use App\Services\AnexosService; //service de lista de anexos pendentes
use Illuminate\Support\Facades\DB; //uso de union no select sem modal
use Livewire\WithFileUploads; //auxilia envio de arquivos
use TallStackUi\Traits\Interactions; //alertas e toasts

class AnexosCreate extends Component
{
    use Interactions;
    use WithFileUploads;

    // Propriedades que serão usadas no formulário
    public ?int $solicitacaoId = null;//id que vem como parametro
    public $observacoes;
    public $tipo_anexo_id;
    public array $tipoAnexo = [];//array para select
    public $arquivo_upload = null;
    public $anexosPendentes = null;//variavel para armazenar lista anexos pendentes

    public bool $open = false;//boleano controlar abertura do modal

    // Este atributo faz o componente "ouvir" o evento disparado pelo botão (parte 1)
    #[On('abrir-anexos-create')]
    public function abrirModal($solicitacaoId)
    {
        $this->resetErrorBag();//// 1. Limpa erros de validação anteriores
        $this->reset(['tipo_anexo_id', 'observacoes', 'arquivo_upload']);//// 2. Limpa os campos para um novo registro

        $this->solicitacaoId = $solicitacaoId;//// 3. Seta o ID da solicitação pai recebido pelo evento

        $this->open = true;// // ABRE O MODAL alterano a variavel

        // DISPARA CARREGAMENTO de dados ASSÍNCRONO, evitando usuario ficar aguardando
        $this->dispatch('carregar-dados-anexo');
    }

    // Este atributo faz o componente "ouvir" o evento disparado pela função acima (parte2)
    #[On('carregar-dados-anexo')]
    public function carregarDados(AnexosService $serviceAnexos)
    {
        $this->verificar($serviceAnexos);//service que verifica os anexos pendentes

        //sql do campo select somente se tiver vindo um parametro
        if ($this->solicitacaoId) {
            $this->tipoAnexo = DB::table('sistec.itens_tipos')
                ->join('sistec.itens_servicos', 'sistec.itens_tipos.id', '=', 'sistec.itens_servicos.itens_tipos_id')
                ->join('sistec.solicitacaos', 'sistec.itens_servicos.servicos_id', '=', 'sistec.solicitacaos.servicos_id')
                ->where('sistec.solicitacaos.id', $this->solicitacaoId)
                ->where('sistec.itens_tipos.tipo_interno', 'anexos')
                ->where('sistec.itens_tipos.ativo', true)
                ->select(
                    'sistec.itens_tipos.id as value',
                    'sistec.itens_tipos.nome as label'
                )
                ->orderBy('sistec.itens_tipos.nome')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->toArray();
        }
    }


    public function salvar(MinioStorageService $service, AnexosService $serviceAnexos)
    {
        $this->validate([
            'tipo_anexo_id' => 'required',
            'solicitacaoId' => 'required',
            'arquivo_upload'=> 'required|file|max:100240',
        ]);

         try {
            $bucket = 'sistec-bucket';
            $diretorio = 'anexos';

            if ($this->arquivo_upload) {
                // 1. dados arquivo ANTES DO UPLOAD
                $extensao = $this->arquivo_upload->getClientOriginalExtension();
                $mime = $this->arquivo_upload->getMimeType();
                $tamanho = $this->arquivo_upload->getSize();
                //$nomeOriginal = $this->arquivo_upload->getClientOriginalName();

                // 3. UPLOAD PARA O MINIO (Passo Crítico)
                // O código só continua se o upload não lançar erro
                $path = $service->salvarArquivo($bucket, $diretorio, $this->arquivo_upload);

                if (!$path) {
                    throw new \Exception("Erro ao enviar arquivo para o storage.");
                }

                //movo mome gerado pelo service
                $nomeNovo = basename($path);

                // 4. Busca o Label do select (como vimos anteriormente)
                $labelSelecionado = collect($this->tipoAnexo)
                    ->firstWhere('value', $this->tipo_anexo_id)['label'] ?? 'Arquivo';

                // 5. Busca o nome do usuário logado (Tabela Users)
                $nomeUsuario = Auth::user()->name;

                // 6. Monta a base da descrição
                $descricaoAutomatica = '[AUTOMÁTICA DO SISTEMA] - Anexo ' . $labelSelecionado . ' adicionado por: ' . $nomeUsuario;

                // 7. Adiciona observações apenas se não estiverem vazias
                if (!empty($this->observacoes)) {
                    $descricaoAutomatica .= ' | Observações do anexo: ' . $this->observacoes;
                }

                // 8. SALVAMENTO NO BANCO (Dentro de transação para segurança total)
                DB::transaction(function () use ($path, $mime, $tamanho, $extensao, $descricaoAutomatica, $nomeNovo) {
                    // Monta o array com as variáveis capturadas no passo 1
                    $data = ([
                        'arquivo_nome'       => $nomeNovo,
                        'arquivo_mime_type'  => $mime,
                        'tamanho_bytes'      => $tamanho,
                        'arquivo_ext'        => $extensao,                // Extensão real
                        'solicitacaos_id'    => $this->solicitacaoId,    // ID da propriedade da classe
                        'arquivo_size'       => $tamanho,                // Repetindo conforme seu código
                        'data'               => now()->toDateString(),   // Data atual Y-m-d
                        'publico'            => true,
                        'itens_tipos_id'     => $this->tipo_anexo_id,    // ID selecionado no Select
                        'observacao'         => $descricaoAutomatica,
                    ]);

                    SolicitacaosAnexos::create($data);//gera o registro do anexo
                    $solicitacao = Solicitacao::findOrFail($this->solicitacaoId);// Buscamos os dados da solicitação
                    // se encontrou, gera a ocorrencia
                    if ($solicitacao) {
                        // array com informações para criar o registro
                        $dataOcorrencia = ([
                            'num_protocolo'         => $solicitacao->num_protocolo,
                            'tipo_ocorrencias_id'   => 78,
                            'data_ocorrencia'       => now(),
                            'descricao'             => $descricaoAutomatica,
                            'usuarios_id'           => 1,
                            'usuario_lotacao'       => 'CETI',
                        ]);
                        Ocorrencias::create($dataOcorrencia);//gera o registro de ocorrencias
                    }
                });

                // 9. LIMPEZA E FEEDBACK
                if ($this->arquivo_upload) {
                    $this->arquivo_upload->delete();
                } //Deleta o temporário local do disco do SAIL
                $this->reset(['tipo_anexo_id', 'observacoes', 'arquivo_upload']); //limpa os campos
                $this->verificar($serviceAnexos); //Atualiza a lista de anexos pendentes novamente
                $this->dispatch('refresh-anexos');//atualizar o blade da table anexos
                $this->dispatch('refresh-ocorrencias');//atualizar o blade da table ocorrencias
                //$this->toast()->success('Sucesso', 'Arquivo salvo!')->send();// aviso de sucesso
                //$this->open = false;//fecha o modal
                $this->dialog()->success('Successo', 'Arquivo salvo!')->send();

            }

        } catch (\Throwable $e) {
            report($e);
            // Exibe o erro real para debug se necessário
            $this->toast()->error('Erro', 'Falha: ' . $e->getMessage())->send();
        }
    }

    public function verificar(AnexosService $serviceAnexos)
    {
        //busca dados solicitacao
        $solicitacao = Solicitacao::findOrFail($this->solicitacaoId);
        $tamanho = preg_replace('/\D/', '', $solicitacao->empresa_cpf_cnpj ?? '');
        $perguntas = $solicitacao->perguntas ?? [];

        //MONTA OS DADOS DINAMICAMENTE
        $dados = [
            'servicos_id' => $solicitacao->servicos_id,
            'natureza_juridicas_id' => $solicitacao->natureza_juridicas_id,
            'prestador_servico' => $solicitacao->prestador_servico,
            'tipo_solicitante_id' => $solicitacao->tipo_solicitante_id,
            'cnpj' => strlen($tamanho) === 14,
            'simplificado' => ($solicitacao->tipo ?? null) === 'S',
            'mei' => ($perguntas['Microempreendedor Individual:'] ?? null) === 'Sim',
            'area' => (float) $solicitacao->area_declarada,
        ];

        $result = $serviceAnexos->executar($dados, $this->solicitacaoId);

        //dd($dados, $result);

        $this->anexosPendentes = $result['mensagem'];
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.anexos-create');
    }
}
