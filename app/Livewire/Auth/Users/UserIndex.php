<?php
namespace App\Livewire\Auth\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;
use TallStackUi\Traits\Interactions;

#[Layout('layouts.auth')]

class UserIndex extends Component
{
    use WithPagination;
    use Interactions;

    public ?int $quantity = 10;
    public ?string $search = null;
    public ?int $userIdToDelete = null;

    // Abre o diálogo
    public function abrir(int $userId): void
    {
        $this->userIdToDelete = $userId;//recebe o id

        $this->dialog()
            ->question('Atenção', 'Tem certeza que deseja deletar este usuário?')
            ->confirm('Sim', 'confirmed')
            ->cancel('Não', 'cancelled')
            ->send();
    }

    // Reinicia a paginação ao digitar na busca
    public function updatingSearch()
    {
        $this->resetPage();
    }
/*
    public function delete(User $user)
    {
        $user->delete();
        // Opcional: Adicione um feedback visual (Toast/Notification)
    }
*/
    // Chamado quando clica em "Sim"
    public function confirmed(): void
    {
        //deleta usuario
        //$user->delete();
        User::findOrFail($this->userIdToDelete)->delete();

        //exibe dialogo no destino
        $this->dialog()
            ->success('Sucesso', 'Usuário deletado com sucesso!')
            ->flash()
            ->send();

        //$this->resetPage(); // melhor UX que redirect
        $this->redirect(route('users.index'), navigate: true);
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
        $headers = [
            ['index' => 'id', 'label' => '#'],
            ['index' => 'name', 'label' => 'Nome'],
            ['index' => 'email', 'label' => 'Email'],
            ['index' => 'action', 'label' => 'Ações'], // Mudado para 'action' (singular)
        ];

        $rows = User::query()
            ->when($this->search, function (Builder $query) {
                return $query->where('name', 'ilike', "%{$this->search}%");
            })
            ->paginate($this->quantity);

        return view('livewire.auth.users.user-index', [
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
