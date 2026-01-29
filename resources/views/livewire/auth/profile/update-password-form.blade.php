<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    <x-card class=" border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
        <x-slot name="header">
            <h3 class="text-base font-semibold">
                {{ __('Atualizar Senha') }}
            </h3>

            <p class="text-sm">
                {{ __('Use uma senha longa e aleatória para manter sua conta segura.') }}
            </p>
        </x-slot>

        <form wire:submit.prevent="updatePassword" class="space-y-6">
            {{-- Senha atual --}}
            <x-input
                label="{{ __('Senha atual') }}"
                type="password"
                wire:model.defer="current_password"
                id="update_password_current_password"
                name="current_password"
                autocomplete="current-password"
                required
            />

            <x-input-error :messages="$errors->get('current_password')" />

            {{-- Nova senha --}}
            <x-input
                label="{{ __('Nova senha') }}"
                type="password"
                wire:model.defer="password"
                id="update_password_password"
                name="password"
                autocomplete="new-password"
                required
            />

            <x-input-error :messages="$errors->get('password')" />

            {{-- Confirmação --}}
            <x-input
                label="{{ __('Confirmar nova senha') }}"
                type="password"
                wire:model.defer="password_confirmation"
                id="update_password_password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                required
            />

            <x-input-error :messages="$errors->get('password_confirmation')" />

            {{-- Footer --}}
            <div class="flex items-center gap-4">
                <x-button
                    type="submit"
                    text="{{ __('Atualizar Senha') }}"
                    color="green"
                />

                <x-action-message on="password-updated">
                    {{ __('Senha atualizada com sucesso.') }}
                </x-action-message>
            </div>

        </form>
    </x-card>

     <div
        wire:loading.flex
        wire:target="updatePassword"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <!-- Adicionado dark:border-white e dark:border-t-transparent -->
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            
            <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
        </div>
    </div>

</section>
