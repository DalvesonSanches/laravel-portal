<?php

namespace App\Livewire\Guest;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Livewire\Actions\Logout;

#[Layout('layouts.guest')]
class VerificarEmail extends Component
{
    /**
     * Envia novamente o e-mail de verificação
     */
    public function sendVerification(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(
                default: route('dashboard', absolute: false),
                navigate: true
            );

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Logout do usuário
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.guest.verificar-email');
    }
}
