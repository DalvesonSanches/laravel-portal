<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="tallstackui_darkTheme()" 
      x-bind:class="{ 'dark': darkTheme }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

   <body class="min-h-screen flex flex-col
             bg-gray-50 text-gray-900
             dark:bg-gray-900 dark:text-gray-100">

        {{-- Componente de dialogo --}}
        <x-dialog />
        <x-toast />
        
        {{-- Cabeçalho --}}
        <x-layouts.portal.header />

        {{-- Navbar responsiva com tema --}}
        {{--  <x-layouts.navbar /> --}}

        {{-- Conteúdo principal --}}
        <main class="flex-1 max-w-7xl mx-auto px-4 py-6">
            {{ $slot }}
        </main>

        {{-- Rodapé --}}
        <x-layouts.portal.footer />

        @livewireScripts
    </body>
</html>
