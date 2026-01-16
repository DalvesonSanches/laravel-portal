<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- BOAS-VINDAS -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="mt-1 text-lg text-gray-600 dark:text-gray-300">
                    Bem-vindo!
                </h3>

                <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <!-- CARDS -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">

                <!-- CARD: NOVA SOLICITAÇÃO -->
                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="block">
                    <x-card
                        class="flex flex-col items-center justify-center text-center p-4
                               w-full aspect-square
                               bg-white dark:bg-gray-800
                               hover:shadow-lg hover:scale-[1.02]
                               transition cursor-pointer"
                    >
                        <x-icon name="document-text"
                                class="w-8 h-8 text-primary-600 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nova Solicitação
                        </span>
                    </x-card>
                </a>

                <!-- CARD: MEUS PROTOCOLOS -->
                <a href="{{ route('dashboard') }}"
                   wire:navigate
                   class="block">
                    <x-card
                        class="flex flex-col items-center justify-center text-center p-4
                               w-full aspect-square
                               bg-white dark:bg-gray-800
                               hover:shadow-lg hover:scale-[1.02]
                               transition cursor-pointer"
                    >
                        <x-icon name="magnifying-glass"
                                class="w-8 h-8 text-primary-600 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            Meus Protocolos
                        </span>
                    </x-card>
                </a>

                <!-- CARD: MEU PERFIL -->
                <a href="{{ route('profile') }}"
                   wire:navigate
                   class="block">
                    <x-card
                        class="flex flex-col items-center justify-center text-center p-4
                               w-full aspect-square
                               bg-white dark:bg-gray-800
                               hover:shadow-lg hover:scale-[1.02]
                               transition cursor-pointer"
                    >
                        <x-icon name="user"
                                class="w-8 h-8 text-primary-600 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            Meu Perfil
                        </span>
                    </x-card>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>