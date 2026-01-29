<?php

namespace App\Livewire\Auth\Profile;

use App\Models\User;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class DeleteUser extends Component
{
    //
    use Interactions;

    public string $password = '';

    // Abre o diálogo
    public function abrir(): void{

        $this->dialog()
            ->question('Atenção', 'Tem certeza que deseja deletar seu usuário?')
            ->confirm('Sim', 'confirmed')
            ->cancel('Não', 'cancelled')
            ->send();
    }

    // Chamado quando clica em "Sim"
    public function confirmed(Logout $logout): void
    {
        /*
        //validação da senha
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);
        */

        $user = Auth::user();
        if ($user instanceof User) {
            $logout($user);
            $user->delete();
        }

        $this->dialog()
            ->success('Sucesso', 'Usuário deletado com sucesso!')
            ->flash()
            ->send();

        $this->redirect(route('login'), navigate: true);
    }

    // Chamado quando clica em "Não"
    public function cancelled(): void
    {
        $this->dialog()
            ->info('Cancelado', 'Procedimento cancelado!')
            ->send();
    }

    public function render()
    {
        return view('livewire.auth.profile.delete-user');
    }
}