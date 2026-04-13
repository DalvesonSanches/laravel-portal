<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\Attributes\On; // Importante para o listener do modal O atributo #[On] serve exclusivamente para ouvir (escutar) eventos
use App\Models\SolicitacaosAnexos;
use App\Models\Solicitacao;
use App\Models\Ocorrencias;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use App\Services\MinioStorageService; //service do minion
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

    // Este atributo faz o componente "ouvir" o evento disparado pelo botão
    #[On('abrir-anexos-create')]
    public function carregarModal($solicitacaoId)
    {
        // 1. Limpa erros de validação anteriores
        $this->resetErrorBag();
        
        // 2. Limpa os campos para um novo registro
        $this->reset([ 'tipo_anexo_id', 'observacoes', 'arquivo_upload']); 
        
        // 3. Seta o ID da solicitação pai recebido pelo evento
        $this->solicitacaoId = $solicitacaoId;

        //sql do campo select somente se tiver vindo um parametro
        if ($solicitacaoId) {
            $this->tipoAnexo = DB::table('sistec.itens_tipos')
                ->join('sistec.itens_servicos', 'sistec.itens_tipos.id', '=', 'sistec.itens_servicos.itens_tipos_id')
                ->join('sistec.solicitacaos', 'sistec.itens_servicos.servicos_id', '=', 'sistec.solicitacaos.servicos_id')
                ->where('sistec.solicitacaos.id', $solicitacaoId)
                ->where('sistec.itens_tipos.tipo_interno', 'anexos')
                ->where('sistec.itens_tipos.ativo', true)
                ->select(
                    'sistec.itens_tipos.id as value', 
                    'sistec.itens_tipos.nome as label'
                )
                ->orderBy('sistec.itens_tipos.nome')
                ->get()
                ->map(fn ($item) => (array) $item) // Converte stdClass para array
                ->toArray();
        }
    }

    public function salvar(MinioStorageService $service)
    {
        $this->validate([
            'tipo_anexo_id' => 'required',
            'solicitacaoId' => 'required',
            'arquivo_upload'=> 'required|file|max:10240',
        ]);

         try {
            $bucket = 'sistec-bucket';
            $diretorio = 'anexos';

            if ($this->arquivo_upload) {
                // 1. GERA UM NOME ÚNICO E SIMPLES (Ex: 5f3e2a1b.pdf)
                $extensao = $this->arquivo_upload->getClientOriginalExtension();
                $novoNome = str()->random(14) . '.' . $extensao; 

                // 2. CAPTURA DADOS ANTES DO UPLOAD
                $mime = $this->arquivo_upload->getMimeType();
                $tamanho = $this->arquivo_upload->getSize();
                $nomeOriginal = $this->arquivo_upload->getClientOriginalName();

                // 3. UPLOAD PARA O MINIO (Passo Crítico)
                // O código só continua se o upload não lançar erro
                $path = $service->salvarArquivo($bucket, $diretorio, $this->arquivo_upload, $novoNome);

                if (!$path) {
                    throw new \Exception("Erro ao enviar arquivo para o storage.");
                }

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
                DB::transaction(function () use ($path, $novoNome, $mime, $tamanho, $extensao, $descricaoAutomatica, $nomeOriginal) {
                    // Monta o array com as variáveis capturadas no passo 1
                    $data = ([
                        'arquivo_path'       => $path,
                        'arquivo_nome'       => $novoNome,
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
                $this->arquivo_upload->delete(); //Deleta o temporário local do disco do SAIL
                $this->toast()->success('Sucesso', 'Arquivo salvo!')->send();// aviso de sucesso
                $this->reset(['tipo_anexo_id', 'observacoes', 'arquivo_upload']); //limpa os campos

                $this->dispatch('refresh-anexos');//atualizar o blade da table anexos
                $this->dispatch('refresh-ocorrencias');//atualizar o blade da table ocorrencias
                //$this->js('$modalClose("anexos-create")');//fecha o modal
            }

        } catch (\Throwable $e) {
            report($e);
            // Exibe o erro real para debug se necessário
            $this->toast()->error('Erro', 'Falha: ' . $e->getMessage())->send();
        }
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.anexos-create');
    }
}
