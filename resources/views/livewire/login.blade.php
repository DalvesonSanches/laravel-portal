<div class="flex justify-center">
    <x-slot:title>
        Acesso ao portral de serviços técnicos
    </x-slot:title>

    <x-card class="w-full max-w-md p-6 space-y-4">

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
            Login - Portal SISTEC
        </h1>

        {{-- 3️⃣ Primeiro acesso --}}
        <p class="text-center text-sm ">
            Primeiro acesso?
            <a
                href="{{ route('registro') }}"
                class="text-blue-600 hover:underline"
                wire:navigate
            >
                Registre-se
            </a>
        </p>

        {{-- Status da sessão --}}
        <x-auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form wire:submit.prevent="login" class="space-y-4">

            {{-- 4️⃣ Email --}}
            <x-input
                label="E-mail"
                type="email"
                wire:model.defer="email"
                required
                autofocus
                autocomplete="username"
            />

            {{-- 5️⃣ Senha --}}
            <x-password
                label="Senha"
                wire:model.defer="password"
                required
                autocomplete="current-password"
            />

            {{-- 6️⃣ Botão Entrar --}}
            <x-button
                type="submit"
                class="w-full"
                color="blue"
            >
                Entrar
            </x-button>

            {{-- 7️⃣ Lembrar-me --}}
            <div class="flex justify-left">
                <x-checkbox
                    label="Lembrar-me"
                    wire:model="remember"
                />
            </div>

            {{-- 8️⃣ Esqueceu a senha --}}
            <p class="text-center text-sm">
                Esqueceu a senha?
                <a
                    href="{{ route('password.request') }}"
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

    <div
        wire:loading.flex
        wire:target="login"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <!-- Adicionado dark:border-white e dark:border-t-transparent -->
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            
            <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
        </div>
    </div>

</div>
