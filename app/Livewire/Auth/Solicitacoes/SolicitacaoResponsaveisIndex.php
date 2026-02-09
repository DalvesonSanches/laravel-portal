<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacaoResponsavel;

class SolicitacaoResponsaveisIndex extends Component
{
    public bool $show = false;
    public ?int $solicitacaoId = null;

    /** obrigatórios para x-table */
    public array $headers = [];
    public $rows;

    protected $listeners = [
        'abrir-solicitacao-responsaveis' => 'abrir',
    ];

    public function mount(): void
    {
        $this->headers = [
            ['index' => 'tipo', 'label' => 'Tipo'],
            ['index' => 'nome', 'label' => 'Nome'],
            ['index' => 'cpf', 'label' => 'CPF'],
            ['index' => 'telefone', 'label' => 'Telefone'],
            ['index' => 'email', 'label' => 'E-mail'],
            ['index' => 'action', 'label' => 'Ações'],
        ];

        $this->rows = collect();
    }

    public function abrir(int $solicitacaoId): void
    {
        $this->solicitacaoId = $solicitacaoId;
        $this->show = true;

        $this->carregarResponsaveis();
    }

    public function fechar(): void
    {
        $this->reset(['show', 'solicitacaoId']);
        $this->rows = collect();
    }

    private function carregarResponsaveis(): void
    {
        $this->rows = SolicitacaoResponsavel::query()
            ->select([
                'solicitacaos_responsaveis.id',
                'tipo_solicitante.tipo',
                'solicitacaos_responsaveis.nome',
                'solicitacaos_responsaveis.cpf',
                'solicitacaos_responsaveis.telefone',
                'solicitacaos_responsaveis.email',
            ])
            ->join(
                'sistec.tipo_solicitante',
                'tipo_solicitante.id',
                '=',
                'solicitacaos_responsaveis.tipo_solicitante_id'
            )
            ->where('solicitacaos_responsaveis.solicitacaos_id', $this->solicitacaoId)
            ->orderBy('solicitacaos_responsaveis.id')
            ->get();
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-responsaveis-index');
    }
}
