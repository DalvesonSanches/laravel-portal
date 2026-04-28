<div>
    <div>
        {{--se a variavel readonly for true exibi o botao--}}
        @if (!$readonly)
            {{--botao modal --}}
            <x-button round
                class="w-full sm:w-auto justify-center"
                icon="paper-clip"
                color="green"
                wire:click="abrirModal({{ $solicitacaosId }})"
                wire:loading.attr="disabled"
                wire:target="abrirModal"
                title="Adicionar um novo anexo"
            >
                {{-- estado normal --}}
                <span wire:loading.remove wire:target="abrirModal">
                    Novo anexo
                </span>

                {{-- loading --}}
                <span wire:loading wire:target="abrirModal">
                    Carregando...
                </span>
            </x-button>
        @endif
        {{--tabela--}}
        <x-table :$headers :$rows striped filter paginate loading>
            {{-- O nome após 'column_' deve ser EXATAMENTE o 'index' do header --}}
            @interact('column_tipo_taxa', $row)
                {{ $row->TiposTaxas->tipo_taxa ?? '-' }}
            @endinteract

            @interact('column_situacao_sql', $row)
                @php
                    $color = match($row->situacao) {
                        'P' => 'green',
                        'A' => 'yellow',
                        'V' => 'red',
                        'C' => 'gray',
                        default => 'primary',
                    };
                @endphp
                <x-badge :text="$row->situacao_sql" :color="$color" light />
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
                            title="Baixar Taxa"
                        />

                        <x-button.circle
                            icon="trash"
                            color="red"
                            wire:click="confirmarExclusao({{ $row->id }})"
                            wire:loading.attr="disabled"
                            title="Cancelar Taxa"
                        />

                    </div>
                @endinteract
            @endif
        </x-table>
    </div>

</div>
