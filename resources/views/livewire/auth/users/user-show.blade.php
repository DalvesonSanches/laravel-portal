<div class="py-5">
    <x-slot:title>
        Detalhes do Usuário
    </x-slot:title>

    <x-slot name="header">
        <h2 class="text-xl text-gray-500 dark:text-gray-400">
            Detalhes:
            <span class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                {{ $user->name }}
            </span>
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>
            <p><strong>Nome:</strong><br> {{ $user->name }}</p>
            <p><strong>Email:</strong><br> {{ $user->email }}</p>
            <p><strong>CPF:</strong><br> {{ $user->cpf }}</p>
            <p><strong>Telefone:</strong><br> {{ $user->telefone }}</p>
            <p><strong>Perfil:</strong><br> {{ $user->role }}</p>
            <x-slot:footer>
                {{-- Ações --}}
                <div class="flex justify-start gap-3 mt-3">
                    <x-button
                        color="yellow"
                        icon="arrow-left"
                        href="{{ route('users.index') }}"
                    >
                        Voltar
                    </x-button>

                    <x-button
                        color="cyan"
                        icon="pencil"
                        href="{{ route('users.edit', $user) }}"
                    >
                        Editar
                    </x-button>
                </div>
            </x-slot:footer>
        </x-card>
    </div>
</div>
