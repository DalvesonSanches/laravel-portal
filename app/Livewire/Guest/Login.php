<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use TallStackUi\Traits\Interactions;

#[Layout('layouts.guest')]
class Login extends Component
{
    use Interactions;
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Session::regenerate();

        $this->redirectIntended(
            default: route('dashboard', absolute: false),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.guest.login');
    }
}