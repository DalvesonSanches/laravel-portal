<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
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
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal de Serviços | CBMAP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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

<body class="font-sans antialiased bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">

<!-- NAVBAR -->
<nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16 items-center">

            <!-- ESQUERDA -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Portal CBMAP
                </a>

                <!-- MENU DESKTOP -->
                <div class="hidden sm:flex space-x-4">
                    <a href="{{ route('dashboard') }}"
                       wire:navigate
                       class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Área do Cidadão
                    </a>

                    <a href="#"
                       wire:navigate
                       class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Serviços
                    </a>
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
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center text-sm font-medium">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 border">
                        <a href="{{ route('profile') }}"
                           wire:navigate
                           class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                            Meu Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Sair do sistema
                            </button>
                        </form>
                    </div>
                </div>
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

            <a href="{{ route('dashboard') }}" wire:navigate class="block text-sm">Área do Cidadão</a>
            <a href="#" wire:navigate class="block text-sm">Serviços</a>
            <a href="{{ route('profile') }}" wire:navigate class="block text-sm">Meu Perfil</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-sm text-red-600">
                    Sair do sistema
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- CABEÇALHO -->
@if (isset($header))
<header class="bg-white shadow dark:bg-gray-800">
    <div class="max-w-7xl mx-auto py-6 px-4">
        {{ $header }}
    </div>
</header>
@endif

<!-- CONTEÚDO -->
<main class="py-6">
    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
