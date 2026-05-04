<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SolicitacaosAnexos;
use App\Models\Ocorrencias;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use App\Services\MinioStorageService; // Importe seu service
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\On; // atributo para gerar eventos listeners O atributo #[On] serve exclusivamente para ouvir (escutar) eventos
use Illuminate\Support\Str;//uso para susbtituir o cpf por asteriscos

class AnexosIndex extends Component
{

    use WithPagination;
    use Interactions;
    public int $quantity = 10;
    public ?string $search = null;// 🔥 necessário para filter
    public array $headers = [];
    public ?string $solicitacaosId = null;
    public ?string $solicitacaosStatus = null; //status da solicitação que veio como parametro
    public ?string $solicitacaosServicosId = null; //servicos_id da solicitação que veio como parametro
    public bool $readonly = false; // Propriedade para receber o readonly

    //Este método define o que aparece na tela enquanto a aba carrega.
    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex flex-col gap-4 p-4 w-full animate-pulse">
            <div class="flex justify-between items-center">
                <div class="h-8 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
                <div class="h-8 bg-gray-200 rounded w-1/4 dark:bg-gray-700"></div>
                <div class="h-8 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
            </div>
            <div class="space-y-3">
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
            </div>
        </div>
        HTML;
    }

    public function mount(?string $solicitacaosId = null, ?string $solicitacaosStatus = null, ?string $solicitacaosServicosId = null, $readonly = false): void
    {
        $this->solicitacaosId = $solicitacaosId;
        $this->solicitacaosStatus = $solicitacaosStatus;
        $this->solicitacaosServicosId = $solicitacaosServicosId;
        $this->readonly = $readonly;
        $this->headers = [
            ['index' => 'tipo_data', 'label' => 'Tipo / Data'],//virtual para concatenar tipo e data
            ['index' => 'observacao', 'label' => 'Observação'],
            ['index' => 'action', 'label' => 'Ação'],
        ];
    }

    //reset da busca
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Método para processar o download ($id vem do blade na opção download)
    public function download(int $id, MinioStorageService $storageService)
    {
        $anexo = SolicitacaosAnexos::findOrFail($id);
        $bucket = 'sistec-bucket';
        $caminhoNoMinio = 'anexos/' . $anexo->arquivo_nome;

        // 1. Validar se o arquivo realmente existe no MinIO
        if (!$storageService->existe($bucket, $caminhoNoMinio)) {
            // Se NÃO existir, dispara o Dialog e encerra a função
            return $this->dialog()
                ->error('Erro de Arquivo', 'O arquivo físico não foi encontrado no servidor Storage.')
                ->send();
        }
        // 2. Se existir, gera a URL
        $url = $storageService->url($bucket, $caminhoNoMinio);
        // 3. Abre em nova aba
        return $this->js("window.open('$url', '_blank')");
    }

    // 1. Método de confirmação
    public function confirmarExclusao(int $id): void
    {
        $this->dialog()
            ->question('Remover Anexo', 'Tem certeza que deseja excluir permanentemente este arquivo?')
            // Passamos o $id como parâmetro para o método 'delete'
            ->confirm('Sim, excluir', 'delete', $id)
            ->cancel('Cancelar')
            ->send();
    }

    // 2. Método de exclusão (agora recebe o ID)
    public function delete(int $id, MinioStorageService $service): void
    {
        try {
            //$anexo = SolicitacaosAnexos::findOrFail($id);
            $anexo = SolicitacaosAnexos::with('itensTipos', 'solicitacao')->findOrFail($id);//carrega o relacionamento automaticamente
            $nomeTipo = $anexo->itensTipos->nome ?? 'Arquivo';//nome do tipo de anexo

            //informações usuario logado
            $nomeUsuario = Auth::user()->name;// 5. Busca o nome do usuário logado (Tabela Users)
            $cpfUsuario = Auth::user()->cpf;// busca o cpf do usuario
            $cpfLimpo = preg_replace('/[^0-9]/', '', $cpfUsuario);// Remove qualquer pontuação caso o CPF venha com pontos/traços do banco
            $cpfFinal = Str::substr($cpfLimpo, 0, 3) . '.***.***-' . Str::substr($cpfLimpo, -2);// Pega os 3 primeiros e os 2 últimos

            $descricao = '[AUTOMÁTICA DO SISTEMA] - Anexo ' . $nomeTipo . ' removido por: ' . $nomeUsuario . 'CPF: ' . $cpfFinal;//descricao do delete na ocorrencia
            $numProtocolo = $anexo->solicitacao->num_protocolo;//numero protocolo atraves do relacionamento belongto
            $bucket = 'sistec-bucket';
            $caminhoNoMinio = 'anexos/' . $anexo->arquivo_nome;

            // Tenta excluir no MinIO
            $service->excluirArquivo($bucket, $caminhoNoMinio);

            // Exclui no Banco de Dados
            $anexo->delete();

            //gera ocorrencia
            Ocorrencias::create([
                'num_protocolo'       => $numProtocolo,
                'tipo_ocorrencias_id' => 101, // exemplo: ID de exclusão
                'data_ocorrencia'     => now(),
                'descricao'           => $descricao,
                'usuarios_id'         =>  1,
                'usuario_lotacao'     => 'CETI',
            ]);

            $this->dispatch('refresh-ocorrencias');//atualizar o blade da table ocorrencias

            $this->toast()->success('Sucesso', 'Arquivo removido com sucesso!')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Falha ao remover arquivo.')->send();
        }
    }

    //abre o modal do create (recebendo $solicitacaosId que vem do blade)
    public function abrirModal($id)
    {
        $this->dispatch('abrir-anexos-create', solicitacaoId: $id);
    }


    // usa o atributo para gerar um evento que refresh de pagina O Livewire detecta o evento e re-executa a query no render()
    #[On('refresh-anexos')]
    public function refresh(){}

    public function render()
    {
        $rows = SolicitacaosAnexos::query()
            ->with('ItensTipos')
            ->where('solicitacaos_id', $this->solicitacaosId)
            ->orderBy('id', 'desc')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('arquivo_nome', 'ilike', "%{$this->search}%")
                    ->orWhere('observacao', 'ilike', "%{$this->search}%") // Se for coluna simples
                    // 👇 Busca dentro do relacionamento "Tipo"
                    ->orWhereHas('ItensTipos', function ($subQuery) {
                        $subQuery->where('nome', 'ilike', "%{$this->search}%");
                    });
                }); // <-- Faltava o ponto e vírgula aqui
            })
            ->paginate($this->quantity);

        return view('livewire.auth.solicitacoes.anexos-index', [
            'rows' => $rows
        ]);
    }
}
