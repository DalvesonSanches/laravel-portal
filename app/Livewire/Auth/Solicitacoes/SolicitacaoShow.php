<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use App\Models\Solicitacao; // Model de solicitação
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')] 
class SolicitacaoShow extends Component
{
    // Removi o solicitacaoId pois você já tem o objeto $solicitacao que contém o ID
    public ?Solicitacao $solicitacao = null;

    public function mount($solicitacaoId): void
    {
        // O segredo está no 'with'. Use o nome EXATO da função que você criou no Model.
        // Se no Model está 'LocalAtendimentos', aqui deve ser igual.
        $this->solicitacao = Solicitacao::with('LocalAtendimentos', 'UnidadeVistoriantes', 'Servico', 'NaturezaJuridicas', 'tipoAltura', 'tipoCarga', 'tipoOcupacao', 'ServicoSubtipo')
            ->findOrFail($solicitacaoId);
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-show');
    }
}