<?php

namespace App\Livewire\Auth\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Validation\ValidationException;
use TallStackUi\Traits\Interactions;

#[Layout('layouts.auth')]
class UserEdit extends Component
{
    use Interactions;
    public User $user;
    public array $form = [];

    protected $rules = [
        'form.name' => 'required|string|min:3',
        'form.email' => 'required|email',
        'form.cpf' => 'nullable|string',
        'form.telefone' => 'nullable|string',
        'form.role' => 'nullable|string',
    ];

    public function mount(User $user)
    {
        $this->user = $user;

        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'cpf' => $user->cpf,
            'telefone' => $user->telefone,
            'role' => $user->role,
        ];
    }

    public function save()
    {
        // 1️⃣ Validação básica
        $this->validate();

        // 2️⃣ Normalização
        $this->normalizeForm();

        // 3️⃣ Validação de CPF verdadeiro (se existir)
        if (!empty($this->form['cpf']) && !$this->cpfValido($this->form['cpf'])) {
            throw ValidationException::withMessages([
                'form.cpf' => 'CPF inválido.',
            ]);
        }

        // 4️⃣ Persistência
        $this->user->update($this->form);

        //dialogo sucesso
        $this->dialog()
            ->success('Sucesso!', 'Usuário atualizado com sucesso!')
            ->flash() 
            ->send();
 
        return $this->redirect(route('users.index'));
    }

    /**
     * 🔹 Normaliza os dados do formulário
     */
    private function normalizeForm(): void
    {
        $this->form['cpf'] = isset($this->form['cpf'])
            ? preg_replace('/\D/', '', $this->form['cpf'])
            : null;

        $this->form['telefone'] = isset($this->form['telefone'])
            ? preg_replace('/\D/', '', $this->form['telefone'])
            : null;

        $this->form['email'] = strtolower(trim($this->form['email']));
    }

    /**
     * 🔹 Valida CPF verdadeiro
     */
    private function cpfValido(string $cpf): bool
    {
        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;
            if ($cpf[$t] != $digito) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        return view('livewire.auth.users.user-edit');
    }
}

