<div class="py-5">
    <x-slot:title>
        Meus Protocolos
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Meus Protocolos
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>
            {{-- montagem tabela --}}
            <x-table :$headers :$rows striped filter loading>
                @interact('column_action', $row)
                    <div class="flex gap-2">
                       <x-button.circle
                            icon="eye"
                            wire:click="$dispatch('abrir-responsaveis', { solicitacaoId: {{ $row->id }} })"
                            color="blue"
                        />
                        {{--icone--}}
                        <x-button.circle
                            icon="share"
                            color="cyan"
                            x-on:click="$dispatch('abrir-solicitacao-responsaveis', { solicitacaoId: {{ $row->id }} })"
                        />
                        <x-button.circle
                            icon="pencil"
                            href="{{ route('dashboard', $row->id) }}"
                            color="yellow"
                        />

                        <x-button.circle
                            icon="trash"
                            wire:click="abrir('{{ $row->id }}')"
                            color="red"
                        />
                    </div>
                @endinteract
            </x-table>

            {{-- Paginação --}}
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </x-card>
    </div>

    {{-- Modal Livewire --}}
   <livewire:auth.solicitacoes.solicitacao-responsaveis-index />
</div>
