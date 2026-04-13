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
        //Buscar solicitação
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
        
         //Detectar tipo da rota
        if (Route::currentRouteNamed('solicitacoes.show.public')) {
            $this->readonly = true;
            return;
        }

        //Fluxo autenticado
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