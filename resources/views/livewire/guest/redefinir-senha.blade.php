<div class="flex justify-center">
    <x-slot:title>
        Redefinir senha portral de serviços técnicos
    </x-slot:title>

    <form wire:submit.prevent="resetPassword" class="space-y-4">
       <x-card class="w-full max-w-lg p-6 space-y-5">
            {{-- 1️⃣ Logo CBMAP --}}
            <div class="flex justify-center">
                <img
                    src="{{ asset('images/logo-cbmap.png') }}"
                    alt="CBMAP"
                    class="h-30"
                >
            </div>

            {{-- 2️⃣ Título --}}
            <h1 class="text-2xl font-bold text-center">
                Redefinir senha - Portal SISTEC
            </h1>

             {{-- E-mail --}}
            <x-input
                label="E-mail"
                type="email"
                wire:model.defer="email"
                required
                readonly
                autofocus
            />

            {{-- Nova senha --}}
            <x-input
                label="Nova senha"
                type="password"
                wire:model.defer="password"
                required
            />

            {{-- Confirmar nova senha --}}
            <x-input
                label="Confirmar senha"
                type="password"
                wire:model.defer="password_confirmation"
                required
            />

            <x-button type="submit" class="w-full" color="blue">
                Redefinir senha
            </x-button>
            <!-- LINHA FINAL -->
            <div class="text-center text-xs py-4 border-t border-gray-500">
                <div>
                    &copy; {{ date('Y') }} Corpo de Bombeiros Militar do Amapá — Todos os direitos reservados.
                </div>
                <div>
                    Desenvolvido por Centro de Tecnologia da Informação - CETI/CBMAP
                </div>
            </div>
       </x-card>
    </form>

    {{-- Overlay global de loading --}}
    <div
        wire:loading.flex
        wire:target="resetPassword"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            <div class="text-gray-900 dark:text-gray-100">
                Processando... aguarde.
            </div>
        </div>
    </div>
</div>