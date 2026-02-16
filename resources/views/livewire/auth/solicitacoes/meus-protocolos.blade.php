<div class="py-5">
    <x-slot:title>
        Meus Protocolos
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Meus Protocolos
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">
        <x-card>
            {{-- montagem tabela --}}
            <x-table :$headers :$rows striped filter loading>
                {{-- quebra de linha nas colunas --}}
                @interact('column_status', $row)
                    <div class="block w-25 whitespace-normal wrap-break-word py-2">
                        {{ $row->status }}
                    </div>
                @endinteract

                @interact('column_endereco', $row)
                    <div class="block w-60 whitespace-normal wrap-break-word py-2">
                        {{ $row->endereco }}
                    </div>
                @endinteract

                @interact('column_empresa_razao_social', $row)
                    <div class="block w-40 whitespace-normal wrap-break-word py-2">
                        {{ $row->empresa_razao_social }}
                    </div>
                @endinteract

                @interact('column_nome_servico', $row)
                    <div class="block w-48 whitespace-normal wrap-break-word py-2">
                        {{ $row->nome_servico }}
                    </div>
                @endinteract
                {{-- barra de aççoes --}}
                @interact('column_action', $row)
                    <div class="flex gap-2">
                       <x-button.circle
                            icon="eye"
                            wire:click="$dispatch('abrir-responsaveis', { solicitacaoId: {{ $row->id }} })"
                            color="blue"
                        />
                        {{--abrindo um modal--}}
                        <x-button.circle
                            icon="share"
                            color="cyan"
                            wire:click="$dispatch('abrir-solicitacao-responsaveis', { solicitacaoId: {{ $row->id }} })"
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

    {{-- componente do modal--}}
   <livewire:auth.solicitacoes.solicitacao-responsaveis-index />
</div>
