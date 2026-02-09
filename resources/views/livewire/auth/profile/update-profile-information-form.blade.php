<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $telefone = '';

    /**
     * Mount the component.
     */
    public function mount(): void {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->telefone = Auth::user()->telefone ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'telefone' => ['required', 'string', 'max:20'],
        ]);

        // remove tudo que não for número
        $validated['telefone'] = preg_replace('/\D/', '', $validated['telefone']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->redirect(route('profile'), navigate: true);
    }


    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void{
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>



<section class="w-full">
     <x-card class=" border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
        <x-slot name="header">
            <h3 class="text-base font-semibold">
                {{ __('Perfil') }}
            </h3>

            <p class="text-sm ">
                {{ __('Informações da sua conta.') }}
            </p>
        </x-slot>

        <form wire:submit.prevent="updateProfileInformation" class="space-y-6">

            {{-- Nome --}}
            <x-input
                label="{{ __('Nome') }}"
                wire:model.defer="name"
                name="name"
                autocomplete="name"
                required
            />

            <x-input-error :messages="$errors->get('name')" />

            {{-- telefone --}}
           <x-input
                label="{{ __('Telefone') }}"
                name="telefone"
                wire:model.defer="telefone"
                x-data
                x-mask="(99) 99999-9999"
                autocomplete="tel"
                required
            />

            <x-input-error :messages="$errors->get('telefone')" />

            {{-- Email --}}
            <x-input
                label="{{ __('Email') }}"
                type="email"
                wire:model.defer="email"
                name="email"
                autocomplete="username"
                required
            />

            <x-input-error :messages="$errors->get('email')" />

            {{-- Verificação de Email --}}
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
                ! auth()->user()->hasVerifiedEmail())

                <x-alert color="warning">
                    <span>
                        {{ __('Seu e-mail não foi verificado.') }}

                        <x-button
                            text="{{ __('Reenviar verificação') }}"
                            wire:click.prevent="sendVerification"
                            color="warning"
                            flat
                            sm
                        />
                    </span>
                </x-alert>

                @if (session('status') === 'verification-link-sent')
                    <x-alert color="success" class="mt-3">
                        {{ __('Um novo link de verificação foi enviado.') }}
                    </x-alert>
                @endif
            @endif

            {{-- Footer --}}
            <div class="flex items-center gap-4">
                <x-button
                    text="{{ __('Atualizar informações') }}"
                    type="submit"
                    color="blue"
                />

                <x-action-message on="profile-updated">
                    {{ __('Atualizado com sucesso.') }}
                </x-action-message>
            </div>

        </form>
    </x-card>


     <div
        wire:loading.flex
        wire:target="updateProfileInformation"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <!-- Adicionado dark:border-white e dark:border-t-transparent -->
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            
            <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
        </div>
    </div>

</section>

