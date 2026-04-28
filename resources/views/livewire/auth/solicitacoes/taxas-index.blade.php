<div>
    <div>
        {{ $solicitacaosStatus }};
        {{ $solicitacaosServicosId }};
        {{ $solicitacaosIsento }};

        {{--se a variavel readonly for true exibi o botao--}}
        @if (!$readonly)
            <div class="mb-4"> {{-- Adicionado margem inferior --}}
                {{-- se o processo não estiver encerrado, cancelado, vencido e empresa não isenta de taxa "1 direto no blade"--}}
                @if(($solicitacaosStatus != 'E' || $solicitacaosStatus != 'C' || $solicitacaosStatus != 'VE') && !$solicitacaosIsento)
                    {{--nao tem taxa de abertura aguardando ou paga (A, p)--}}
                    @if (!$this->temTaxaAbertura)
                        {{--abrir modal de taxa de abertura de processo--}}
                        <x-button round
                            class="w-full sm:w-auto justify-center"
                            icon="currency-dollar"
                            color="green"
                            wire:click="abrirModal({{ $solicitacaosId }})"
                            wire:loading.attr="disabled"
                            wire:target="abrirModal"
                            title="Taxa de abertura de processo"
                        >
                            {{-- estado normal --}}
                            <span wire:loading.remove wire:target="abrirModal">
                                Taxa de abertura
                            </span>

                            {{-- loading --}}
                            <span wire:loading wire:target="abrirModal">
                                Carregando...
                            </span>
                        </x-button>
                    {{--se tem taxa de abertura aguardando ou paga (A, p)--}}
                    @else
                        {{--se relatorio ja estiver assinado (nome_arquivo preenchido e numero_autenticacao)--}}
                        @if($this->temRelatorio->nome_arquivo && $this->temRelatorio->nome_arquivo)
                            {{--//verifica se tem pendencia de diferença de area não paga--}}
                            @if ($this->temPendenciasTaxas->id)
                                {{--busca o boleto de diferença--}}



                            @endif
                        @endif


                    @endif
                @endif
                {{--abrir modal de taxa de diferença--}}
                <x-button round
                    class="w-full sm:w-auto justify-center"
                    icon="currency-dollar"
                    color="green"
                    wire:click="abrirModal({{ $solicitacaosId }})"
                    wire:loading.attr="disabled"
                    wire:target="abrirModal"
                    title="Taxa de diferença"
                >
                    {{-- estado normal --}}
                    <span wire:loading.remove wire:target="abrirModal">
                        Taxa de diferença
                    </span>

                    {{-- loading --}}
                    <span wire:loading wire:target="abrirModal">
                        Carregando...
                    </span>
                </x-button>
                {{--abrir modal de taxa de 5 relatorio--}}
                <x-button round
                    class="w-full sm:w-auto justify-center"
                    icon="currency-dollar"
                    color="green"
                    wire:click="abrirModal({{ $solicitacaosId }})"
                    wire:loading.attr="disabled"
                    wire:target="abrirModal"
                    title="Taxa de 5º relatório"
                >
                    {{-- estado normal --}}
                    <span wire:loading.remove wire:target="abrirModal">
                        Taxa de 5º relatório
                    </span>

                    {{-- loading --}}
                    <span wire:loading wire:target="abrirModal">
                        Carregando...
                    </span>
                </x-button>
                {{--abrir modal de taxa de reativação de processo--}}
                <x-button round
                    class="w-full sm:w-auto justify-center"
                    icon="currency-dollar"
                    color="green"
                    wire:click="abrirModal({{ $solicitacaosId }})"
                    wire:loading.attr="disabled"
                    wire:target="abrirModal"
                    title="Taxa de reativação de processo"
                >
                    {{-- estado normal --}}
                    <span wire:loading.remove wire:target="abrirModal">
                        Taxa de reativação
                    </span>

                    {{-- loading --}}
                    <span wire:loading wire:target="abrirModal">
                        Carregando...
                    </span>
                </x-button>
            </div>
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
