<div class="py-5">
    <x-slot:title>
        Perfil
    </x-slot:title>

    <!-- HEADER -->
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <!-- CONTENT -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- INFORMAÇÕES DO PERFIL -->
            <x-card>
                <!-- Header -->
                <x-slot:header>
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Informações do Perfil') }}
                        </h2>
                    </div>
                </x-slot:header>

                <div class="w-full">
                    <livewire:auth.profile.update-profile-information-form />
                </div>
            </x-card>

            <!-- ALTERAR SENHA -->
            <x-card>
                <!-- Header -->
                <x-slot:header>
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Alterar Senha') }}
                        </h2>
                    </div>
                </x-slot:header>

                <div class="w-full">
                    <livewire:auth.profile.update-password-form />
                </div>
            </x-card>

            <!-- EXCLUIR CONTA -->
            <x-card class="border border-red-200 dark:border-red-700">
                 <!-- Header -->
                <x-slot:header>
                    <div class="px-6 py-5 border-red-200 dark:border-red-700">
                        <h2 class="text-base font-semibold text-red-600 dark:text-red-400">
                            {{ __('Excluir Conta') }}
                        </h2>
                    </div>
                </x-slot:header>

                <div class="w-full">
                    <livewire:auth.profile.delete-user />
                </div>
            </x-card>

        </div>
    </div>
</div>

