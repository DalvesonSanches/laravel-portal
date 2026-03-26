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

    // Gera a URL temporária para o arquivo no MinIO
    public function getDownloadUrl(int $id, MinioStorageService $service): ?string
    {
        $download = Downloads::find($id);
        if (!$download || !$download->arquivo_path) return null;

        return $service->url('portal-bucket', $download->arquivo_path);
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

        return view('livewire.portal.downloads-consulta', ['rows' => $rows]);
    }
}
