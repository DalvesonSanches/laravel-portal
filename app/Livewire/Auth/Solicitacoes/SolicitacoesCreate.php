<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\Attributes\Layout;
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\Validate;
use App\Livewire\Auth\Solicitacoes\FormObjectEmpresasSolicitantes;


#[Layout('layouts.auth')]
class SolicitacoesCreate extends Component
{
    use Interactions;

    // 1. Mantenha a tipagem, mas o Livewire precisa que o objeto seja instanciado
    public FormObjectEmpresasSolicitantes $solicitanteForm;

    public int $step = 1;
    public string $is_same_owner = '';
    public string $name = '';
    public string $email = '';
    public string $teste = '';
    public string $teste1 = '';
    

    public function next(): void
    {
        if ($this->step === 1) {
            $this->validate([
                'is_same_owner' => 'required',
                'name'          => 'required|min:3'
            ]);

            if ($this->is_same_owner === 'nao') {
                // Agora o objeto está inicializado e o método existe
                $this->solicitanteForm->validate(); 
            }
        } 
        elseif ($this->step === 2) {
            $this->validate(['email' => 'required|email']);
        }

        $this->step++;
    }

    public function previous(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function save(): void
    {
        if ($this->is_same_owner === 'nao') {
            $this->solicitanteForm->validate();
        }
        
        $this->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $this->toast()->success('Sucesso!')->send();
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacoes-create');
    }
}

