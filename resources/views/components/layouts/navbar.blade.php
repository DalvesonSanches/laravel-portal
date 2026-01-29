<nav x-data="{ open:false }" 
     class="bg-white shadow border-b border-gray-200">
    
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        {{-- MENU DESKTOP --}}
        <div class="hidden md:flex items-center gap-8">

            <a wire:navigate href="{{ route('home') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700">
                <i class="fa-regular fa-house text-2xl leading-none"></i>
                Início
            </a>

            <a wire:navigate href="{{ route('home') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700">
                <i class="fa-regular fa-file-lines text-2xl leading-none"></i>
                Nova Certidão 
            </a>

            <a wire:navigate href="{{ route('home') }}" 
               class="flex items-center gap-2 hover:text-primary-600 text-gray-700">
               <i class="fa-solid fa-magnifying-glass text-2xl leading-none"></i>
                Consultar Certidão
            </a>
        </div>

        {{-- AÇÃO À DIREITA (theme toggle + hamburger) --}}
        <div class="flex items-center gap-3">

            {{-- TEMA DARK/LIGHT
            <x-theme.toggle /> --}}

            {{-- HAMBURGUER (mobile) --}}
            <button 
                @click="open = !open"
                class="md:hidden p-2 rounded text-gray-700 hover:bg-gray-100">
                <i class="fa-solid fa-bars text-2xl leading-none"></i>
            </button>
        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div 
        x-show="open"
        x-transition.origin.top
        class="md:hidden bg-white px-4 py-4 space-y-3 border-t border-gray-200">

        <a wire:navigate href="{{ route('home') }}" 
           class="flex items-center gap-2 text-gray-700 hover:text-primary-600">
            <i class="fa-regular fa-house text-2xl leading-none"></i>
            Início
        </a>

        <a wire:navigate href="{{ route('home') }}" 
           class="flex items-center gap-2 text-gray-700 hover:text-primary-600">
            <i class="fa-regular fa-file-lines text-2xl leading-none"></i>
            Nova Certidão
        </a>

        <a wire:navigate href="{{ route('home') }}" 
           class="flex items-center gap-2 text-gray-700 hover:text-primary-600">
           <i class="fa-solid fa-magnifying-glass text-2xl leading-none"></i>
            Consultar Certidão
        </a>

    </div>

</nav>