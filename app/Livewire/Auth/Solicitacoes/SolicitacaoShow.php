<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use App\Models\Solicitacao;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//#[Layout('layouts.auth')]
class SolicitacaoShow extends Component
{
   use AuthorizesRequests;

    public ?Solicitacao $solicitacao = null;

    public bool $readonly = false;

    public function mount($autenticidade): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Buscar solicitação
        |--------------------------------------------------------------------------
        */

        $this->solicitacao = Solicitacao::with(
            'LocalAtendimentos',
            'UnidadeVistoriantes',
            'Servico',
            'NaturezaJuridicas',
            'tipoAltura',
            'tipoCarga',
            'tipoOcupacao',
            'ServicoSubtipo'
        )->where('autenticidade', $autenticidade)
         ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Detectar tipo da rota
        |--------------------------------------------------------------------------
        */

        if (Route::currentRouteNamed('solicitacoes-show-public')) {
            $this->readonly = true;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Fluxo autenticado
        |--------------------------------------------------------------------------
        */

        $this->readonly = false;

        $this->authorize('view', $this->solicitacao);
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-show')
            ->layout(
                $this->readonly
                    ? 'layouts.portal'
                    : 'layouts.auth'
            );
    }
}
/*
namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use App\Models\Solicitacao; // Model de solicitação
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')] 
class SolicitacaoShow extends Component
{
    // Removi o solicitacaoId pois você já tem o objeto $solicitacao que contém o ID
    public ?Solicitacao $solicitacao = null;

    public function mount($autenticidade): void
    {
        // O segredo está no 'with'. Use o nome EXATO da função que você criou no Model.
        // Se no Model está 'LocalAtendimentos', aqui deve ser igual.
        $this->solicitacao = Solicitacao::with(
            'LocalAtendimentos',
            'UnidadeVistoriantes',
            'Servico',
            'NaturezaJuridicas',
            'tipoAltura',
            'tipoCarga',
            'tipoOcupacao',
            'ServicoSubtipo'
        )->where('autenticidade', $autenticidade)
        ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-show');
    }
}
    */