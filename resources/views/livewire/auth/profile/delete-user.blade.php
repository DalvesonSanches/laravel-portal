<div>
    <x-card class=" border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
        <x-slot name="header">
            <h2 class="text-lg font-medium text-red-600">
                {{ __('Remover sua conta') }}
            </h2>

            <p class="mt-1 mb-4 text-sm text-red-400">
                {{ __('O procedimento de remover sua conta é permanente, antes disso faça backup das informações importantes.') }}
            </p>
        </x-slot>



        <x-button
            color="red"
            wire:click="abrir"
        >
            Remover minha conta
        </x-button>
    </x-card>

    <div
        wire:loading.flex
        wire:target="confirmed"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <!-- Adicionado dark:border-white e dark:border-t-transparent -->
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            
            <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
        </div>
    </div>
</div>