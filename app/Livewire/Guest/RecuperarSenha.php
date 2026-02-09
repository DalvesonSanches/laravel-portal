<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;

#[Layout('layouts.guest')]
class RecuperarSenha extends Component
{
    public string $email = '';

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }

    /**
     * Envia o link de redefinição de senha
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }

    public function render()
    {
        return view('livewire.guest.recuperar-senha');
    }
}