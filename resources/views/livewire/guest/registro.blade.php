<div class="flex justify-center">
    <x-slot:title>
        Novo usuário portral de serviços técnicos
    </x-slot:title>

    <x-card class="w-full max-w-lg p-6 space-y-5" header="Novo usuário - Portal SISTEC" color="cyan" bordered>

        {{-- 1️⃣ Logo CBMAP --}}
        <div class="flex justify-center">
            <img
                src="{{ asset('images/logo-cbmap.png') }}"
                alt="CBMAP"
                class="h-30"
            >
        </div>

        <form wire:submit.prevent="register" class="space-y-4">

            {{-- 3️⃣ Nome --}}
            <x-input
                label="Nome"
                wire:model.defer="name"
                required
                autofocus
            />

            {{-- 4️⃣ CPF + Telefone --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input
                    label="CPF"
                    wire:model.defer="cpf"
                    required
                    x-data
                    x-mask="999.999.999-99"
                />

                <x-input
                    label="Telefone"
                    wire:model.defer="telefone"
                    required
                    x-data
                    x-mask="(99) 99999-9999"
                />
            </div>

            {{-- 5️⃣ Email --}}
            <x-input
                label="E-mail"
                type="email"
                wire:model.defer="email"
                required
            />

            {{-- 6️⃣ Senha --}}
            <x-password
                label="Senha"
                wire:model.defer="password"
                required
            />

            {{-- 7️⃣ Confirmar senha --}}
            <x-password
                label="Confirmar senha"
                wire:model.defer="password_confirmation"
                required
            />

            {{-- 8️⃣ Botão Registrar --}}
            <x-button type="submit"
                class="w-full"
                color="blue"
            >
                Registrar
            </x-button>

            {{-- 9️⃣ Já possui acesso --}}
            <p class="text-center text-sm">
                Já possui acesso?
                <a
                    href="{{ route('login') }}"
                    class="text-blue-600 hover:underline"
                    wire:navigate
                >
                    Clique aqui
                </a>
            </p>

            <!-- LINHA FINAL -->
            <div class="text-center text-xs py-4 border-t border-gray-500">
                <div>
                    &copy; {{ date('Y') }} Corpo de Bombeiros Militar do Amapá — Todos os direitos reservados.
                </div>
                <div>
                    Desenvolvido por Centro de Tecnologia da Informação - CETI/CBMAP
                </div>
            </div>

        </form>
    </x-card>

    {{-- Overlay global de loading --}}
    <div
        wire:loading.flex
        wire:target="register"
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