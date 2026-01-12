<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="tallstackui_darkTheme()"
    x-bind:class="{ 'dark': darkTheme }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Sincroniza dark mode antes do Alpine -->
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

        applyDarkTheme();
        document.addEventListener('livewire:navigated', applyDarkTheme);
    </script>

    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ isset($title) ? $title . ' | portal de serviços técnicos CBMAP' : 'DISCIP | CBMAP' }}</title>
</head>

<body
    class="min-h-screen flex items-center justify-center
           bg-gray-100 text-gray-900
           dark:bg-gray-900 dark:text-gray-100
           transition-colors">

    {{ $slot }}

    @livewireScripts
</body>
</html>

