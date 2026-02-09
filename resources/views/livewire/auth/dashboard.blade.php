<div>
    <x-slot:title>
        Dashboard
    </x-slot:title>

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
                <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
                    wire:navigate href="{{ route('solicitacoes.create') }}"> 
                    <i class="fa-solid fa-file-circle-plus text-5xl text-blue-500" > </i>
                    <h2 class="text-2xl font-semibold ">Nova Solicitação</h2>
                    <p">
                        Clique para iniciar uma nova solicitação.
                    </p>
                </x-card>

                <!-- CARD: MEUS PROTOCOLOS -->
                <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
                    wire:navigate href="{{ route('meus-protocolos') }}">
                    <i class="fas fa-search text-5xl text-yellow-500" > </i>
                    <h2 class="text-2xl font-semibold">Meus Protocolo</h2>
                    <p>
                        Clique para consultar todos os seus protocolos.
                    </p>
                </x-card>

                <!-- CARD: MEU PERFIL -->
                <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
                    wire:navigate href="{{ route('profile') }}">
                    <i class="fa-solid fa-user text-5xl text-cyan-950" > </i>
                    <h2 class="text-2xl font-semibold">Meu Perfil</h2>
                    <p>
                        Clique para gerenciar suas informações cadastrais.
                    </p>
                </x-card>
            </div>
        </div>
    </div>
</div>