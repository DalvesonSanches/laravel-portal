<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SolicitacaosAnexos;
use App\Services\MinioStorageService; // Importe seu service
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\On; // atributo para gerar eventos listeners O atributo #[On] serve exclusivamente para ouvir (escutar) eventos

class AnexosIndex extends Component
{

    use WithPagination;
    use Interactions;
    public int $quantity = 10;
    public ?string $search = null;// 🔥 necessário para filter
    public array $headers = [];
    public ?string $solicitacaosId = null;
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

    public function mount(?string $solicitacaosId = null, $readonly = false): void
    {
        $this->solicitacaosId = $solicitacaosId;
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

    // Método para processar o download
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
            $anexo = SolicitacaosAnexos::findOrFail($id);
            $bucket = 'sistec-bucket';
            $caminhoNoMinio = 'anexos/' . $anexo->arquivo_nome;

            // Tenta excluir no MinIO
            $service->excluirArquivo($bucket, $caminhoNoMinio);

            // Exclui no Banco de Dados
            $anexo->delete();

            $this->toast()->success('Sucesso', 'Arquivo removido com sucesso!')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Falha ao remover arquivo.')->send();
        }
    }

    //abre o modal do create
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
