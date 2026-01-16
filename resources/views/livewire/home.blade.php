<div class="container mx-auto px-4 py-10">
    <x-slot:title>
        Inicio
    </x-slot:title>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-4">
        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('login') }}"> 
            <i class="fa-solid fa-file-circle-plus text-5xl text-blue-500" > </i>
            <h2 class="text-2xl font-semibold ">Solicitar Serviço</h2>
            <p">
                Clique para iniciar o procedimento de solicitação de fiscalização de analise de projetos, vistoria para certificação, credenciamento, vistoria em parque ou vistoria em shows.
            </p>
        </x-card>

        {{-- Card 2 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('home') }}">
            <i class="fas fa-search text-5xl text-yellow-500" > </i>
            <h2 class="text-2xl font-semibold">Consultar Protocolo</h2>
            <p>
                Clique para consultar o andamento da sua solicitação através do número de protocolo.
            </p>
        </x-card>

        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('login') }}"> 
            <i class="fa-brands fa-whatsapp text-5xl text-green-500" > </i>
            <h2 class="text-2xl font-semibold ">Atendimento Digital</h2>
            <p">
                Clique para iniciar o chat do whatsapp com um militar do atendimento.
            </p>
        </x-card>

        {{-- Card 2 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('home') }}">
            <i class="fa-solid fa-building text-5xl text-shadow-dark-800" > </i>
            <h2 class="text-2xl font-semibold">Empresas Regularizadas</h2>
            <p>
                Clique aqui para consultar a relação de empresas regulares.
            </p>
        </x-card>

        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('login') }}"> 
            <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500" > </i>
            <h2 class="text-2xl font-semibold ">Verificar Autenticação</h2>
            <p">
                Clique aqui para consultar a autenticidade de documentos.
            </p>
        </x-card>

        {{-- Card 2 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('home') }}">
            <i class="fa-solid fa-scale-balanced text-5xl text-cyan-500" > </i>
            <h2 class="text-2xl font-semibold">Leis e Normas</h2>
            <p>
                lique aqui para acessar as Leis e Normas Técnicas da segurança contra incêndio e pânico do CBMAP.
            </p>
        </x-card>

        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('login') }}"> 
            <i class="fa-solid fa-file-invoice-dollar text-5xl text-purple-700" > </i>
            <h2 class="text-2xl font-semibold ">Taxas</h2>
            <p">
                Clique para realizar a simulação do valor da taxa para serviços de segurança contra incêndio e pânico.
            </p>
        </x-card>

        {{-- Card 2 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('home') }}">
            <i class="fa-solid fa-fire-extinguisher text-5xl text-orange-500" > </i>
            <h2 class="text-2xl font-semibold">Empresas Credenciadas</h2>
            <p>
                Relação de empresas aptas a prestar serviço de segurança contra incêndio e pânico.
            </p>
        </x-card>

        {{-- Card 1 --}}
        <x-card class="flex flex-col items-center justify-center text-center p-6 space-y-3 hover:shadow-lg transition rounded-2xl cursor-pointer"
            wire:navigate href="{{ route('login') }}"> 
            <i class="fa-solid fa-download text-5xl text-gray-500" > </i>
            <h2 class="text-2xl font-semibold ">Downloads</h2>
            <p">
                Clique aqui para consultar a relação de documentos disponiveis para download.
            </p>
        </x-card>

    </div>
</div>
