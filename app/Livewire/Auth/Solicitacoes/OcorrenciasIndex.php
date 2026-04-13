<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ocorrencias;
use Livewire\Attributes\On; // atributo para gerar eventos listeners

class OcorrenciasIndex extends Component
{
    use WithPagination;

    public int $quantity = 10;

    // 🔥 necessário para filter
    public ?string $search = null;

    public array $headers = [];

    public ?string $numProtocolo = null;

    public function mount(?string $numProtocolo = null): void
    {

        $this->numProtocolo = $numProtocolo;
        $this->headers = [
            ['index' => 'tipo_data', 'label' => 'Tipo'],
            ['index' => 'descricao', 'label' => 'Descrição'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // usa o atributo para gerar um evento que refresh de pagina O Livewire detecta o evento e re-executa a query no render()
    #[On('refresh-ocorrencias')] 
    public function refresh(){}

    public function render()
    {
        $rows = Ocorrencias::query()
            ->with('TipoOcorrencias') // 🔥 aqui está o belongsTo
            ->where('num_protocolo', $this->numProtocolo) //condição
            ->orderBy('id', 'desc') 
            ->when($this->search, function ($query) { //search
                $query->where(function ($q) {
                    $q->where('descricao', 'ilike', "%{$this->search}%")
                    ->orWhereHas('TipoOcorrencias', function ($tipo) {
                        $tipo->where('tipo', 'ilike', "%{$this->search}%");
                    });
                });
            })
            ->paginate($this->quantity); //paginação
        return view('livewire.auth.solicitacoes.ocorrencias-index', [
            'rows' => $rows
        ]);
    }
}
