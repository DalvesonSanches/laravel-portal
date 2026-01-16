<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal de Serviços | CBMAP') }}</title>

    <!-- TallStackUI + Livewire + Vite -->
    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Reaplica tema após navegação SPA -->
    <script>
        function applyDarkTheme() {
            const isDark = localStorage.getItem('dark-theme') === 'true';
            document.documentElement.classList.toggle('dark', isDark);
        }
        document.addEventListener('livewire:navigated', applyDarkTheme);
    </script>
</head>

<body
    x-data="{
        darkTheme:
            localStorage.getItem('dark-theme') === 'true' ||
            (
                !localStorage.getItem('dark-theme') &&
                window.matchMedia('(prefers-color-scheme: dark)').matches
            ),
        mobileMenu: false
    }"
    x-init="
        $watch('darkTheme', value => {
            localStorage.setItem('dark-theme', value)
            document.documentElement.classList.toggle('dark', value)
        });
        document.documentElement.classList.toggle('dark', darkTheme);
    "
    class="min-h-screen flex flex-col font-sans antialiased
           bg-gray-100 text-gray-900
           dark:bg-gray-900 dark:text-gray-100"
>

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <!-- para logout -->
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
            </form>

            <div class="flex justify-between h-16 items-center">

                <!-- ESQUERDA -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}"
                    wire:navigate
                    class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        Portal SISTEC
                    </a>

                    <!-- MENU DESKTOP -->
                    <div class="hidden sm:flex space-x-4">
                         <x-dropdown text="Serviços">
                            <x-slot:header>
                                <p class="text-sm font-medium">Selecione</p>
                            </x-slot:header>
                            <x-dropdown.items
                                icon="document-plus"
                                text="Nova Solicitação"
                                href="{{ route('dashboard') }}"
                                wire:navigate
                            />
                            <x-dropdown.items
                                icon="magnifying-glass"
                                text="Meus Protocolos"
                                href="{{ route('dashboard') }}"
                                wire:navigate separator
                            />
                        </x-dropdown>
                    </div>
                </div>

                <!-- DIREITA DESKTOP -->
                <div class="hidden sm:flex items-center space-x-4">

                    <!-- BOTÃO TEMA (DESKTOP) -->
                    <button @click="darkTheme = !darkTheme"
                            class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 transition">
                        <template x-if="darkTheme">
                            <x-icon name="sun" class="w-5 h-5 text-yellow-400" />
                        </template>
                        <template x-if="!darkTheme">
                            <x-icon name="moon" class="w-5 h-5 text-gray-700" />
                        </template>
                    </button>

                    <!-- USUÁRIO -->
                    <x-dropdown text="{{ Auth::user()->name }} ">
                        <x-dropdown.items
                            icon="user"
                            text="Meu Perfil"
                            href="{{ route('profile') }}"
                            wire:navigate
                        />
                        <x-dropdown.items
                            icon="arrow-right-on-rectangle"
                            text="Sair do sistema"
                            separator
                            x-on:click.prevent="document.getElementById('logout-form').submit()"
                        />
                    </x-dropdown>

                </div>

                <!-- BOTÃO HAMBÚRGUER (MOBILE) -->
                <button @click="mobileMenu = !mobileMenu"
                        class="sm:hidden p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>
        </div>

        <!-- MENU MOBILE -->
        <div x-show="mobileMenu" x-transition class="sm:hidden border-t bg-white dark:bg-gray-800">
            <div class="px-4 py-4 space-y-3">

                <!-- BOTÃO TEMA (MOBILE) -->
                <button @click="darkTheme = !darkTheme"
                        class="flex items-center gap-2 w-full px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-700">
                    <template x-if="darkTheme">
                        <x-icon name="sun" class="w-5 h-5 text-yellow-400" />
                    </template>
                    <template x-if="!darkTheme">
                        <x-icon name="moon" class="w-5 h-5" />
                    </template>
                    <span x-text="darkTheme ? 'Modo claro' : 'Modo escuro'"></span>
                </button>

                <!-- MENU SERVICOS -->
                <x-dropdown text="Serviços">
                    <x-slot:header>
                        <p class="text-sm font-medium">Selecione</p>
                    </x-slot:header>
                    <x-dropdown.items
                        icon="document-plus"
                        text="Nova Solicitação"
                        href="{{ route('dashboard') }}"
                        wire:navigate
                    />
                    <x-dropdown.items
                        icon="magnifying-glass"
                        text="Meus Protocolos"
                        href="{{ route('dashboard') }}"
                        wire:navigate separator
                    />
                </x-dropdown>
                <!-- MENU PERFIL -->
                <x-dropdown text="{{ Auth::user()->name }}">
                    <x-dropdown.items
                        icon="user"
                        text="Meu Perfil"
                        href="{{ route('profile') }}"
                        wire:navigate
                    />
                    <x-dropdown.items
                        icon="arrow-right-on-rectangle"
                        text="Sair do sistema"
                        separator
                        x-on:click.prevent="document.getElementById('logout-form').submit()"
                    />
                </x-dropdown>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO -->
    <main class="flex-1 py-6">
        {{ $slot }}
    </main>

    <!-- RODAPE -->
    <footer class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- COLUNA 1 - INSTITUCIONAL -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building text-primary-600"></i>
                    Institucional
                </h3>

                <ul class="space-y-2 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-fire text-primary-600 mt-1"></i>
                        Corpo de Bombeiros Militar do Amapá
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-globe text-primary-600 mt-1"></i>
                        <a href="https://bombeiros.portal.ap.gov.br/" 
                        class="hover:underline" target="_blank">
                            https://bombeiros.portal.ap.gov.br/
                        </a>
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-map-location-dot text-primary-600 mt-1"></i>
                        Rua Hamilton Silva, nº 1647, bairro Santa Rita - Cep: 68.900-068, Macapá - AP
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-code text-primary-600 mt-1"></i>
                        Desenvolvido por Centro de Tecnologia da Informação - CETI/CBMAP
                    </li>
                </ul>
            </div>

            <!-- COLUNA 2 - ACESSO -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-link text-primary-600"></i>
                    Acesso
                </h3>

                <ul class="space-y-2 text-sm">

                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-house text-primary-600"></i>
                        <a wire:navigate href="{{ route('dashboard') }}" class="hover:underline">
                            Início
                        </a>
                    </li>

                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-file-circle-plus text-primary-600"></i>
                        <a wire:navigate href="{{ route('home') }}" class="hover:underline">
                            Nova Solicitação
                        </a>
                    </li>

                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-primary-600"></i>
                        <a wire:navigate href="{{ route('home') }}" class="hover:underline">
                            Meus Protocolos
                        </a>
                    </li>

                </ul>
            </div>

        </div>

        <!-- LINHA FINAL -->
        <div class="text-center text-xs py-4 border-t border-gray-500">
            &copy; {{ date('Y') }} Corpo de Bombeiros Militar do Amapá — Todos os direitos reservados.
            
            <div class="flex justify-center gap-6 mt-6">
                <a href="#" class="hover:text-[#bb2a2a] transition">
                    <i class="fa-brands fa-facebook-f text-lg"></i>
                </a>

                <a href="#" class="hover:text-[#bb2a2a] transition">
                    <i class="fa-brands fa-x-twitter text-lg"></i>
                </a>

                <a href="#" class="hover:text-[#bb2a2a] transition">
                    <i class="fa-brands fa-youtube text-lg"></i>
                </a>

            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
