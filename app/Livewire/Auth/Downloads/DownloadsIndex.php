<?php

namespace App\Livewire\Auth\Downloads;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Downloads;
use App\Services\MinioStorageService; // Importe o seu Service
use TallStackUi\Traits\Interactions;  // Importe para usar o Dialog

#[Layout('layouts.auth')]
class DownloadsIndex extends Component
{
    use WithPagination, Interactions;

    public int $quantity = 10;
    public ?string $search = null;
    public ?int $idParaExcluir = null;
    public array $headers = [];

    public function mount(): void
    {
        $this->headers = [
            ['index' => 'categoria', 'label' => 'Categoria'],
            ['index' => 'titulo', 'label' => 'Titulo'],
            ['index' => 'finalidade', 'label' => 'Finalidade'],
            ['index' => 'descricao', 'label' => 'Descrição'],
            ['index' => 'action', 'label' => 'Ações'],
        ];
    }

    // Gera a URL temporária para o arquivo no MinIO
    public function getDownloadUrl(int $id, MinioStorageService $service): ?string
    {
        $download = Downloads::find($id);
        if (!$download || !$download->arquivo_path) return null;

        return $service->url('portal-bucket', $download->arquivo_path);
    }

    //Aciona o Dialog de confirmação do TallStackUI
    public function confirmarExclusao(int $id): void
    {
        // Guardamos o ID na memória do componente para usar depois
        $this->idParaExcluir = $id;

        $this->dialog()
            ->question('Atenção', 'Deseja realmente excluir este arquivo permanentemente?')
            // O TallStackUI chamará o método 'delete' sem parâmetros
            ->confirm('Sim', 'delete')
            ->cancel('Não', 'cancelled')
            ->send();
    }

    //Executa a exclusão física e lógica
    public function delete(MinioStorageService $service): void
    {
        // Recuperamos o ID que guardamos no passo anterior
        if (!$this->idParaExcluir) return;

        $registro = Downloads::findOrFail($this->idParaExcluir);

        try {
            $service->excluirArquivo('portal-bucket', $registro->arquivo_path);//no minio
            $registro->delete();//no BD

            $this->toast()->success('Sucesso', 'Registro e arquivo removidos!')->send();
            
            // Limpamos o ID para a próxima exclusão
            $this->idParaExcluir = null;
            
        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Não foi possível remover o arquivo físico.')->send();
        }
    }

    // Chamado quando clica em "Não"
    public function cancelled(): void
    {
        $this->toast()->info('Cancelado', 'Procedimento cancelado!')->send();
    }

    // reseta a busca
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Corrigido a query de busca (removido whereHas se não houver relação interna)
        $rows = Downloads::query()
            ->when($this->search, function ($query) {
                $search = "%{$this->search}%";
                $query->where(function($q) use ($search) {
                    $q->whereRaw("unaccent(categoria) ILIKE unaccent(?)", [$search])
                      ->orWhereRaw("unaccent(titulo) ILIKE unaccent(?)", [$search])
                      ->orWhereRaw("unaccent(finalidade) ILIKE unaccent(?)", [$search])
                      ->orWhereRaw("unaccent(descricao) ILIKE unaccent(?)", [$search]);
                });
            })
            ->paginate($this->quantity);

        return view('livewire.auth.downloads.downloads-index', ['rows' => $rows]);
    }
}
