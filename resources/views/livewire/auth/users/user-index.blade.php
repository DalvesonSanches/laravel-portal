<div class="py-5">
    <x-slot:title>
        Usuários
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Usuários
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>
             {{-- ADICIONADO o atributo id="users-table" --}}
            <x-table :$headers :$rows striped filter loading id="users-table">
                
                {{-- Mudado para 'column_action' acompanhando o PHP --}}
                @interact('column_action', $user)
                    <div class="flex gap-2">
                        {{-- Use href em vez de link para garantir a navegação --}}
                        <x-button.circle icon="eye" href="{{ route('users.show', $user->id) }}" color="blue" />
                        
                        <x-button.circle icon="pencil" href="{{ route('users.edit', $user->id) }}" color="yellow"/>
                        
                        <x-button.circle 
                            icon="trash" 
                            {{-- Passando o ID como string conforme o exemplo oficial --}}
                            wire:click="abrir('{{ $user->id }}')" 
                            color="red"
                        />
                    </div>
                @endinteract
            </x-table>
            
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </x-card>
    </div>
</div>
