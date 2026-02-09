<header
    x-data="{ open: false }"
    class="bg-[#bb2a2a] text-white dark:bg-[#7a1a1a] dark:text-gray-100 py-6 shadow border-b border-gray-200 dark:border-gray-700 transition-colors duration-200 ">

    <div class="max-w-7xl mx-auto px-6">
        <!-- Topo: logo + nome + menu -->
        <div class="flex items-center justify-between">
            
            <!-- Logo + Nome -->
            <div class="flex items-center space-x-3">
                <!-- Logo (desktop e mobile com tamanhos diferentes) -->
                <img 
                    src="/images/logo-cbmap.png" 
                    alt="Logo CBM" 
                    class="w-auto md:h-13 md:w-auto h-10"
                >

                <!-- Texto exibido SOMENTE no desktop -->
                <span class="hidden md:block font-semibold text-2xl leading-tight text-center">
                    <span class="block text-base">Corpo de Bombeiros Militar</span>
                    <span class="block text-base">do Amapá</span>
                </span>

                <!-- Texto exibido SOMENTE no mobile -->
                <span class="block md:hidden font-semibold text-xl">
                    CBMAP
                </span>
            </div>


            <!-- Botão Hambúrguer (visível em mobile) -->
            <button 
                @click="open = !open"
                class="md:hidden text-white text-3xl focus:outline-none">
                <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
            </button>

            <!-- MENU DESKTOP -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a wire:navigate href="{{ route('home') }}" 
                    class="flex items-center gap-2 hover:text-primary-200">
                    <i class="fa-regular fa-house text-2xl leading-none"></i> Início
                </a>

                <!-- DROPDOWN SERVIÇOS -->
                <div x-data="{ open: false }" class="relative">

                    <button
                        @click="open = !open"
                        @click.outside="open = false"

                        class="flex items-center gap-2 hover:text-primary-200 transition-colors"
                    >
                        <i class="fa-solid fa-briefcase text-2xl leading-none"></i>
                        Serviços
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div
                        x-show="open"
                        x-transition
                        @click="open = !open"
                        @click.outside="open = false"
                        class="absolute left-0 mt-2 w-56 rounded-lg
                            bg-white dark:bg-gray-800
                            border border-gray-200 dark:border-gray-700
                            shadow-lg z-50"
                    >
                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-file-circle-plus w-4 text-center"></i>
                            Solicitar Serviço
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fas fa-search w-4 text-center"></i>
                            Consultar protocolo
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-brands fa-whatsapp w-4 text-center"></i>
                            Atendimento digital
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-building w-4 text-center"></i>
                            Empresas Regularizadas
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-triangle-exclamation w-4 text-center"></i>
                            Verificar Autenticidade
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-scale-balanced w-4 text-center"></i>
                            Leis e Normas
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i>
                            Taxas
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-fire-extinguisher w-4 text-center"></i>
                            Empresas Credenciadas
                        </a>

                        <a wire:navigate
                        href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm
                                text-gray-800 dark:text-gray-200
                                hover:bg-gray-300 dark:hover:bg-gray-500
                                transition-colors">
                                <i class="fa-solid fa-download w-4 text-center"></i>
                            Downloads
                        </a>
                    </div>

                </div>

                <!-- botão para alternar o tema -->
                <button x-on:click="darkTheme = !darkTheme" class="p-2 rounded-full bg-gray-200">
                    <!-- Mostra o sol se for dark, lua se for light -->
                    <template x-if="darkTheme">
                        <x-icon name="sun" class="w-6 h-6 text-yellow-400" />
                    </template>
                    <template x-if="!darkTheme">
                        <x-icon name="moon" class="w-6 h-6 text-gray-600" />
                    </template>
                </button>

                <!-- login -->
                <a wire:navigate href="{{ route('login') }}" 
                    class="flex items-center gap-2 hover:text-primary-200">
                    <i class="fa-solid fa-user-shield text-2xl leading-none"></i> Entrar
                </a>

            </nav>
        </div>

        <!-- MENU MOBILE -->
        <nav 
            x-show="open"
            x-transition
            class="md:hidden mt-4 bg-[#bb2a2a] rounded-lg p-4 space-y-4 text-sm font-medium">
            
            <a wire:navigate href="{{ route('home') }}" 
                class="flex items-center gap-2 hover:text-primary-200">
                <i class="fa-regular fa-house text-xl"></i> Início
            </a> 
            
            <!-- SERVIÇOS - MOBILE -->
            <div x-data="{ openServices: false }" class="space-y-2">

                <!-- Botão Serviços -->
                <button
                    @click="openServices = !openServices"
                    class="w-full flex items-center justify-between gap-2 hover:text-primary-200"
                >
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-xl"></i>
                        Serviços
                    </span>

                    <i
                        class="fa-solid fa-chevron-down text-xs transition-transform"
                        :class="{ 'rotate-180': openServices }"
                    ></i>
                </button>

                <!-- Itens -->
                <div x-show="openServices" x-transition class="pl-6 space-y-2">

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-file-circle-plus w-4 text-center"></i>
                        Solicitar Serviço
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fas fa-search w-4 text-center"></i>
                        Consultar Protocolo
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-brands fa-whatsapp w-4 text-center"></i>
                        Atendimento Digital
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-building w-4 text-center"></i>
                        Empresas Regularizadas
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-triangle-exclamation w-4 text-center"></i>
                        Verificar Autenticidade
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-scale-balanced w-4 text-center"></i>
                        Leis e Normas
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i>
                        Taxas
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-fire-extinguisher w-4 text-center"></i>
                        Empresas Credenciadas
                    </a>

                    <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-2 text-sm hover:text-primary-200">
                        <i class="fa-solid fa-download w-4 text-center"></i>
                        Downloads
                    </a>

                </div>
            </div>

            <!-- botão para alternar o tema -->
            <button x-on:click="darkTheme = !darkTheme" class="p-2 rounded-full bg-gray-200">
                <!-- Mostra o sol se for dark, lua se for light -->
                <template x-if="darkTheme">
                    <x-icon name="sun" class="w-5 h-5 text-yellow-400" />
                </template>
                <template x-if="!darkTheme">
                    <x-icon name="moon" class="w-5 h-5 text-gray-600" />
                </template>
            </button>

            <!-- login -->
            <a wire:navigate href="{{ route('login') }}" 
                class="flex items-center gap-2 hover:text-primary-200">
                <i class="fa-solid fa-user-shield text-2xl leading-none"></i> Entrar
            </a>
        </nav>

        <!-- Linha divisória -->
        <div class="border-b border-white/20 mt-6"></div>

        <!-- Conteúdo central -->
        <div class="text-center mt-9 pb-1">

            {{-- TÍTULO --}}
            <h1 class="font-extrabold tracking-wider drop-shadow-lg">

                {{-- Desktop --}}
                <span class="text-6xl hidden md:inline">
                    <i class="fa-solid fa-fire-extinguisher text-6xl"></i>
                    Portal SISTEC
                </span>

                {{-- Mobile --}}
                <span class="text-3xl inline md:hidden">
                    <i class="fa-solid fa-fire-extinguisher"></i>
                    Portal SISTEC
                </span>

            </h1>

            {{-- SUBTÍTULO --}}
            <p class="mt-2 text-sm md:text-base font-medium">
                Certificações · Análises de Projetos · Credenciamentos · Parques de Diversões · e Atividades Eventuais
            </p>

            {{-- DATA --}}
            <p class="mt-3 text-sm opacity-90" id="dataAtual"></p>

        </div>

    </div>
</header>

<script>
    // função para formatar a data
    function atualizarData() {
        const dt = new Date();
        const opcoes = { day: 'numeric', month: 'long', year: 'numeric' };
        const el = document.getElementById('dataAtual');
        if (el) {
            el.innerText = dt.toLocaleDateString('pt-BR', opcoes);
        }
    }

    // Carrega a primeira vez
    atualizarData();

    // Livewire navigation recarrega a data após cada troca de página
    document.addEventListener("livewire:navigated", () => {
        atualizarData();
    });
</script>
