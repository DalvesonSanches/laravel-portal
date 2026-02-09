<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="tallstackui_darkTheme()" 
      x-bind:class="{ 'dark': darkTheme }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
            function applyDarkTheme() {
                const isDark =
                    localStorage.getItem('dark-theme') === 'true' ||
                    (
                        !localStorage.getItem('dark-theme') &&
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                    );

                document.documentElement.classList.toggle('dark', isDark);
            }

            // Primeira carga
            applyDarkTheme();

            // Após navegação SPA (wire:navigate)
            document.addEventListener('livewire:navigated', applyDarkTheme);
        </script>

        <!-- Scripts do TallStackUI e Vite -->
        <tallstackui:script /> 
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <title>{{ isset($title) ? $title . ' | portal de serviços técnicos CBMAP' : 'DISCIP | CBMAP' }}</title>
</head>

<body
    x-data="{
        mobileMenu: false
    }"
    class="min-h-screen flex flex-col
           bg-gray-50 text-gray-900
           dark:bg-gray-900 dark:text-gray-100"
>


    {{-- Dialog global --}}
    <x-dialog />

    {{-- Navbar --}}
    <x-layouts.auth.navbar />

    {{-- Cabeçalho opcional --}}
    @if (isset($header))
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    {{-- Conteúdo --}}
    <main class="flex-1 py-6">
        {{ $slot }}
    </main>

    {{-- Rodapé --}}
    <x-layouts.auth.footer />

    @livewireScripts
</body>
</html>