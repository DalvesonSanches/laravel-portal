<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Downloads;
use App\Services\MinioStorageService; // Importe o seu Service
use TallStackUi\Traits\Interactions;  // Importe para usar o Dialog

#[Layout('layouts.portal')]

class DownloadsConsulta extends Component
{
    use WithPagination, Interactions;

    public int $quantity = 10;
    public ?string $search = null;
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

    // reseta a busca
    public function updatingSearch()
    {
        $this->resetPage();
    }

     // Método para processar o download
    public function download(int $id, MinioStorageService $storageService)
    {
        $download = Downloads::findOrFail($id);
        $bucket = 'portal-bucket';
        $caminhoNoMinio = $download->arquivo_path;
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

        return view('livewire.portal.downloads-consulta', ['rows' => $rows]);
    }
}
