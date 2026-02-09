<nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Formulário Global de Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <div class="flex justify-between h-16 items-center">

            <!-- ESQUERDA -->
            <div class="flex items-center space-x-6">
                <a
                    href="{{ route('dashboard') }}"
                    wire:navigate
                    class="flex items-center space-x-4 text-lg font-semibold text-gray-800 dark:text-gray-100"
                >
                    <img
                        src="/images/logo-cbmap.png"
                        alt="CBMAP"
                        class="h-10 w-auto"
                    >

                    <span>Portal SISTEC</span>
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
                            href="{{ route('solicitacoes.create') }}"
                            wire:navigate
                        />
                        <x-dropdown.items
                            icon="magnifying-glass"
                            text="Meus Protocolos"
                            href="{{ route('meus-protocolos') }}"
                            wire:navigate separator
                        />
                    </x-dropdown>
                </div>

                <!-- MENU ADMIN -->
                <div class="hidden sm:flex space-x-4">
                        <x-dropdown text="Admin">
                        <x-slot:header>
                            <p class="text-sm font-medium">Selecione</p>
                        </x-slot:header>
                        <x-dropdown.items
                            icon="user-group"
                            text="Usuarios"
                            href="{{ route('users.index') }}"
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
                        class="p-2 rounded-full bg-gray-200 transition">
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

            <!-- MENU SERVIÇOS (ACCORDION - MOBILE) -->
            <div x-data="{ openServices: false }" class="space-y-2">

                <!-- Botão -->
                <button
                    @click="openServices = !openServices"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-md
                        bg-gray-100 dark:bg-gray-700"
                >
                    <span class="flex items-center gap-2 font-medium">
                        <i class="fa-solid fa-briefcase w-5 text-center"></i>
                        Serviços
                    </span>

                    <i
                        class="fa-solid fa-chevron-down text-sm transition-transform"
                        :class="{ 'rotate-180': openServices }"
                    ></i>
                </button>

                <!-- Itens -->
                <div x-show="openServices" x-transition class="pl-6 space-y-2">

                    <a wire:navigate href="{{ route('solicitacoes.create') }}"
                    class="flex items-center gap-2 px-2 py-1 rounded-md
                            hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-file-circle-plus w-4 text-center"></i>
                        Nova Solicitação
                    </a>

                    <a wire:navigate href="{{ route('meus-protocolos') }}"
                    class="flex items-center gap-2 px-2 py-1 rounded-md
                            hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-magnifying-glass w-4 text-center"></i>
                        Meus Protocolos
                    </a>

                </div>
            </div>

            <!-- MENU ADMIN(ACCORDION - MOBILE) -->
            <div x-data="{ openAdmin: false }" class="space-y-2">

                <!-- Botão -->
                <button
                    @click="openAdmin = !openAdmin"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-md
                        bg-gray-100 dark:bg-gray-700"
                >
                    <span class="flex items-center gap-2 font-medium">
                        <i class="fa-solid fa-user-lock w-5 text-center"></i>
                        Admin
                    </span>

                    <i
                        class="fa-solid fa-chevron-down text-sm transition-transform"
                        :class="{ 'rotate-180': openAdmin }"
                    ></i>
                </button>

                <!-- Itens -->
                <div x-show="openAdmin" x-transition class="pl-6 space-y-2">

                    <a wire:navigate href="{{ route('users.index') }}"
                    class="flex items-center gap-2 px-2 py-1 rounded-md
                            hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-user-group w-4 text-center"></i>
                        Usuários
                    </a>

                    <a wire:navigate href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-2 py-1 rounded-md
                            hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-magnifying-glass w-4 text-center"></i>
                        Meus Protocolos
                    </a>

                </div>
            </div>

            <!-- MENU PERFIL (ACCORDION - MOBILE) -->
            <div x-data="{ openProfile: false }" class="space-y-2">

                <!-- Botão Perfil -->
                <button
                    @click="openProfile = !openProfile"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-md
                        bg-gray-100 dark:bg-gray-700"
                >
                    <span class="flex items-center gap-2 font-medium">
                        <i class="fa-solid fa-user w-5 text-center"></i>
                        {{ Auth::user()->name }}
                    </span>

                    <i
                        class="fa-solid fa-chevron-down text-sm transition-transform"
                        :class="{ 'rotate-180': openProfile }"
                    ></i>
                </button>

                <!-- Itens -->
                <div x-show="openProfile" x-transition class="pl-6 space-y-2">

                    <a wire:navigate href="{{ route('profile') }}"
                    class="flex items-center gap-2 px-2 py-1 rounded-md
                            hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-user-gear w-4 text-center"></i>
                        Meu Perfil
                    </a>

                    <button
                        x-on:click.prevent="document.getElementById('logout-form').submit()"
                        class="flex items-center gap-2 w-full text-left px-2 py-1 rounded-md
                            text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/30">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                        Sair do sistema
                    </button>

                </div>
            </div>

        </div>
    </div>
</nav>