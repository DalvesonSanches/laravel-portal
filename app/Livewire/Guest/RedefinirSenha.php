<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

#[Layout('layouts.guest')]
class RedefinirSenha extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount do componente
     */
    public function mount(string $token): void{
        $this->token = $token;
        $this->email = request()->string('email');
    }

    /**
     * Redefine a senha do usuário
     */
    public function resetPassword(): void{
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }

    public function render()
    {
        return view('livewire.guest.redefinir-senha');
    }
}