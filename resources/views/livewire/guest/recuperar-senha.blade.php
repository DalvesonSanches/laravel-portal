<div>
    <x-slot:title>
        Redefinir senha portral de serviços técnicos
    </x-slot:title>

    <x-card class="w-full max-w-lg p-6 space-y-5" header="Recuperar senha - Portal SISTEC" color="cyan" bordered>
        {{-- 1️⃣ Logo CBMAP --}}
            <div class="flex justify-center">
                <img
                    src="{{ asset('images/logo-cbmap.png') }}"
                    alt="CBMAP"
                    class="h-30"
                >
            </div>

        <div class="mb-4 text-sm ">
            {{ __('Esqueceu sua senha? Sem problema. Basta nos informar seu endereço de e-mail e enviaremos um link para redefinição de senha, que permitirá que você escolha uma nova.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-4">
            <x-input
                label="E-mail"
                type="email"
                wire:model.defer="email"
                required
                autofocus
            />

            <div class="flex justify-end pt-4">
                <x-button type="submit" class="w-full" color="blue">
                    Enviar link de redefinição
                </x-button>
            </div>
        </form>
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

    {{-- Overlay global de loading --}}
    <div
        wire:loading.flex
        wire:target="sendPasswordResetLink"
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