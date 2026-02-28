<div class="space-y-4">
    {{-- Título da página --}}
    <x-slot:title>
        Verificação de e-mail – Portal de Serviços Técnicos
    </x-slot:title>

    <x-card class="w-full max-w-lg p-6 space-y-5" header=" Verificação de e-mail - Portal SISTEC" color="cyan" bordered>

        {{-- 1️⃣ Logo CBMAP --}}
        <div class="flex justify-center">
            <img
                src="{{ asset('images/logo-cbmap.png') }}"
                alt="CBMAP"
                class="h-30"
            >
        </div>

        {{-- Mensagem principal --}}
        <x-alert>
            Para continuar, é necessário confirmar seu endereço de e-mail.
            Enviamos um link de verificação para o e-mail informado no cadastro.
            <br><br>
            Caso não encontre a mensagem, verifique também a caixa de spam.
        </x-alert>

        {{-- Mensagem de sucesso ao reenviar --}}
        @if (session('status') === 'verification-link-sent')
            <x-alert color="green">
                Um novo link de verificação foi enviado para o seu e-mail.
            </x-alert>
        @endif

        {{-- Botão principal --}}
        <x-button
            wire:click="sendVerification"
            class="w-full"
            color="blue"
        >
            Reenviar e-mail de verificação
        </x-button>

        {{-- Link de sair --}}
        <p class="text-center text-sm">
            Deseja acessar com outro usuário?
            <a
                href="#"
                wire:click.prevent="logout"
                class="text-blue-600 hover:underline"
            >
                Clique aqui para sair
            </a>
        </p>

    </x-card>

    {{-- Overlay global de loading --}}
    <div
        wire:loading.flex
        wire:target="sendVerification,logout"
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