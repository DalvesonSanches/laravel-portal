<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ocorrencias;
use Illuminate\Support\Facades\DB;

class OcorrenciasIndex extends Component
{
    use WithPagination;

    public int $quantity = 10;

    // 🔥 necessário para filter
    public ?string $search = null;

    public array $headers = [];

    public ?int $solicitacaoId = null;

    public function mount(?int $solicitacaoId = null): void
    {
        $this->solicitacaoId = $solicitacaoId;

        $this->headers = [
            ['index' => 'tipo_data', 'label' => 'Tipo'],
            ['index' => 'descricao', 'label' => 'Descrição'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $rows = Ocorrencias::query()
            ->select([
                'ocorrencias.id',
                DB::raw("
                    tipo_ocorrencias.tipo || ' em: ' || 
                    TO_CHAR(ocorrencias.data_ocorrencia, 'DD/MM/YYYY') 
                    AS tipo_data
                "),
                'ocorrencias.descricao'
            ])
            ->join(
                'sistec.tipo_ocorrencias',
                'ocorrencias.tipo_ocorrencias_id',
                '=',
                'tipo_ocorrencias.id'
            )
            ->join(
                'sistec.solicitacaos',
                'ocorrencias.num_protocolo',
                '=',
                'solicitacaos.num_protocolo'
            )
            ->where('solicitacaos.id', $this->solicitacaoId)

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('ocorrencias.descricao', 'ilike', "%{$this->search}%")
                      ->orWhere('tipo_ocorrencias.tipo', 'ilike', "%{$this->search}%");
                });
            })

            ->paginate($this->quantity);

        return view('livewire.auth.solicitacoes.ocorrencias-index', [
            'rows' => $rows
        ]);
    }
}
