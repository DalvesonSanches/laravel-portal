<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.guest')]
class ConfirmarSenha extends Component
{
    public string $password = '';

    protected function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Confirma a senha do usuário autenticado
     */
    public function confirmPassword(): void
    {
        $this->validate();

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(
            default: route('dashboard', absolute: false),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.guest.confirmar-senha');
    }
}