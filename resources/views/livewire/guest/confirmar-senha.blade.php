<div>
    <x-card class="w-full p-6 space-y-6" header="Confirmar senha - Portal SISTEC" color="cyan" bordered>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form wire:submit="confirmPassword" class="space-y-4">
            <x-password
                label="Senha"
                wire:model.defer="password"
                required
                autocomplete="current-password"
            />

            <div class="flex justify-end pt-4">
                <x-button type="submit">
                    Confirmar
                </x-button>
            </div>
        </form>
    </x-card>
</div>