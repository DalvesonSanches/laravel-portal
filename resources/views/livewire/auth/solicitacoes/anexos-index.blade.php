<div>
    <div>
        {{--se a variavel readonly for true exibi o botao--}}
        @if (!$readonly)
            {{--botao modal --}}
            <div class="mb-4 flex flex-col sm:flex-row gap-2">
                <x-button round
                    class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
                    icon="paper-clip"
                    color="green"
                    text="Novo anexo"
                    wire:click="$dispatch('abrir-anexos-create', { solicitacaoId: {{ $solicitacaosId }} } )"
                    wire:loading.attr="disabled"
                    title="Adicionar um novo anexo"
                />
            </div>
        @endif
        {{--tabela--}}
        <x-table :$headers :$rows striped filter paginate loading>
            {{-- O nome após 'column_' deve ser EXATAMENTE o 'index' do header --}}
            @interact('column_tipo_data', $row)
                <div class="block w-55 whitespace-normal wrap-break-word">
                    <span class="font-semibold">
                        {{ $row->ItensTipos->nome ?? 'Sem Tipo' }}
                    </span>
                    <br>
                    <span class="text-sm text-gray-500">
                        em: {{ $row->data ? \Carbon\Carbon::parse($row->data)->format('d/m/Y') : '-' }}
                    </span>
                </div>
            @endinteract

            {{-- quebra de linha descrição --}}
            @interact('column_observacao', $row)
                <div class="block w-150 whitespace-normal wrap-break-word">
                    {{ $row->observacao }}
                </div>
            @endinteract

            {{-- Coluna de Ação com ícone de download --}}
            {{--se a variavel readonly for true exibi o botao--}}
            @if (!$readonly)
                @interact('column_action', $row)
                    <div class="flex gap-2" wire:loading.class="opacity-50 pointer-events-none"> {{-- Esmaece os botões ao carregar --}}
                        <x-button.circle
                            icon="cloud-arrow-down"
                            color="blue"
                            wire:click="download({{ $row->id }})"
                            wire:loading.attr="disabled"
                            title="Baixar Arquivo"
                        />

                        <x-button.circle
                            icon="trash"
                            color="red"
                            wire:click="confirmarExclusao({{ $row->id }})"
                            wire:loading.attr="disabled"
                            title="Remover Arquivo"
                        />

                    </div>
                @endinteract
            @endif
        </x-table>
    </div>

    {{-- componente do modal--}}
    <livewire:auth.solicitacoes.anexos-create />
</div>
