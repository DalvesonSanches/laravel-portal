<div>
    {{$solicitacaosStatus}};
    {{$solicitacaosIsento}};
    <div>
         {{--se a variavel readonly for true exibi o botao--}}
        @if (!$readonly)
            <div class="mb-4"> {{-- Adicionado margem inferior --}}
                {{-- se o processo não estiver encerrado, cancelado, vencido e empresa não isenta de taxa "1 direto no blade"--}}
                @if($solicitacaosStatus != 'E' && $solicitacaosStatus != 'C' && $solicitacaosStatus != 'VE' && $solicitacaosStatus != 'SU' && !$solicitacaosIsento)
                    {{--nao tem taxa de abertura aguardando ou paga (A, p)--}}
                    @if (!$this->temTaxaAbertura)
                        {{--abrir modal de taxa de abertura de processo--}}
                        <x-button round
                            class="w-full sm:w-auto justify-center"
                            icon="currency-dollar"
                            color="green"
                            wire:click="abrirModalTaxaAbertura({{ $solicitacaosId }})"
                            wire:loading.attr="disabled"
                            wire:target="abrirModalTaxaAbertura"
                            title="Taxa de abertura de processo"
                        >
                            {{-- estado normal --}}
                            <span wire:loading.remove wire:target="abrirModalTaxaAbertura">
                                Taxa de abertura
                            </span>

                            {{-- loading --}}
                            <span wire:loading wire:target="abrirModalTaxaAbertura">
                                Carregando...
                            </span>
                        </x-button>
                    {{--se tem taxa de abertura aguardando ou paga (A, p)--}}
                    @else
                        {{--se relatorio ja estiver assinado (nome_arquivo preenchido e numero_autenticacao)--}}
                        @if($this->temRelatorio->nome_arquivo && $this->temRelatorio->nome_arquivo)
                            {{--verifica se tem pendencia de diferença de area não paga--}}
                            @if ($this->temPendenciasTaxas)
                                @if ($this->temPendenciasTaxas->id)
                                    {{--busca o boleto de diferença--}}
                                    @if ($this->temtaxaDiferencaArea)
                                        {{--verifica se a taxa ja venceu ou esta cancelada--}}
                                        @if ($this->temtaxaDiferencaArea->situacao == 'V' || $this->temtaxaDiferencaArea->situacao == 'C')
                                            {{--abrir modal de taxa de diferença--}}
                                            <x-button round
                                                class="w-full sm:w-auto justify-center"
                                                icon="currency-dollar"
                                                color="green"
                                                wire:click="abrirModalTaxaDiferenca({{ $solicitacaosId }})"
                                                wire:loading.attr="disabled"
                                                wire:target="abrirModalTaxaDiferenca"
                                                title="Taxa de diferença"
                                            >
                                                {{-- estado normal --}}
                                                <span wire:loading.remove wire:target="abrirModalTaxaDiferenca">
                                                    Taxa de diferença
                                                </span>

                                                {{-- loading --}}
                                                <span wire:loading wire:target="abrirModalTaxaDiferenca">
                                                    Carregando...
                                                </span>
                                            </x-button>
                                        @endif
                                    {{--se não tem o boleto de diferença--}}
                                    @else
                                        {{--abrir modal de taxa de diferença--}}
                                        <x-button round
                                            class="w-full sm:w-auto justify-center"
                                            icon="currency-dollar"
                                            color="green"
                                            wire:click="abrirModalTaxaDiferenca({{ $solicitacaosId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="abrirModalTaxaDiferenca"
                                            title="Taxa de diferença"
                                        >
                                            {{-- estado normal --}}
                                            <span wire:loading.remove wire:target="abrirModalTaxaDiferenca">
                                                Taxa de diferença
                                            </span>

                                            {{-- loading --}}
                                            <span wire:loading wire:target="abrirModalTaxaDiferenca">
                                                Carregando...
                                            </span>
                                        </x-button>
                                    @endif
                                @endif
                            @endif
                            {{--verifica se tem taxa pendente de 5 relatorio--}}
                            @if ($this->temPendencia5Relatorio)
                                {{--se ja tem taxa verifica se ele esta vencido ou cancelado--}}
                                @if ($this->temPendencia5Relatorio->boletos_id)
                                        {{--verifica se tem taxa de 5 relatorio--}}
                                        @if ($this->temTaxa5Relatorio)
                                            {{--verifica se a taxa ja venceu ou esta cancelada--}}
                                            @if($this->temTaxa5Relatorio->situacao == 'V' || $this->temTaxa5Relatorio->situacao == 'C')
                                                {{--abrir modal de taxa de 5 relatorio--}}
                                                <x-button round
                                                    class="w-full sm:w-auto justify-center"
                                                    icon="currency-dollar"
                                                    color="green"
                                                    wire:click="abrirModalTaxa5Relatorio({{ $solicitacaosId }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="abrirModalTaxa5Relatorio"
                                                    title="Taxa de 5º relatório"
                                                >
                                                    {{-- estado normal --}}
                                                    <span wire:loading.remove wire:target="abrirModalTaxa5Relatorio">
                                                        Taxa de 5º relatório
                                                    </span>

                                                    {{-- loading --}}
                                                    <span wire:loading wire:target="abrirModalTaxa5Relatorio">
                                                        Carregando...
                                                    </span>
                                                </x-button>
                                            @endif
                                        {{--se ainda não tiver a taxa de 5 relatorio--}}
                                        @else
                                               {{--abrir modal de taxa de 5 relatorio--}}
                                                <x-button round
                                                    class="w-full sm:w-auto justify-center"
                                                    icon="currency-dollar"
                                                    color="green"
                                                    wire:click="abrirModalTaxa5Relatorio({{ $solicitacaosId }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="abrirModalTaxa5Relatorio"
                                                    title="Taxa de 5º relatório"
                                                >
                                                    {{-- estado normal --}}
                                                    <span wire:loading.remove wire:target="abrirModalTaxa5Relatorio">
                                                        Taxa de 5º relatório
                                                    </span>

                                                    {{-- loading --}}
                                                    <span wire:loading wire:target="abrirModalTaxa5Relatorio">
                                                        Carregando...
                                                    </span>
                                                </x-button>
                                        @endif
                                @endif
                            @endif
                        @endif
                    @endif
                @endif
                {{-- se o processo não estiver suspenso e empresa não isenta de taxa "1 direto no blade"--}}
                @if( $solicitacaosStatus == 'SU' && !$solicitacaosIsento)
                   {{--nao tem taxa de suspensao aguardando ou paga (A, p)--}}
                    @if (!$this->temTaxaSuspenso)
                        {{--abrir modal de taxa de reativação de processo--}}
                        <x-button round
                            class="w-full sm:w-auto justify-center"
                            icon="currency-dollar"
                            color="green"
                            wire:click="abrirModalTaxaSuspensao({{ $solicitacaosId }})"
                            wire:loading.attr="disabled"
                            wire:target="abrirModalTaxaSuspensao"
                            title="Taxa de reativação de processo"
                        >
                            {{-- estado normal --}}
                            <span wire:loading.remove wire:target="abrirModalTaxaSuspensao">
                                Taxa de reativação
                            </span>

                            {{-- loading --}}
                            <span wire:loading wire:target="abrirModalTaxaSuspensao">
                                Carregando...
                            </span>
                        </x-button>
                    @endif
                @endif
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
