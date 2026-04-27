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
                {{ $row->TiposTaxas->tipo_taxa ?? 'Sem Tipo' }}
            @endinteract

            {{-- Formatação da Data de Vencimento --}}
            @interact('column_data_vencimento', $row)
                {{ $row->data_vencimento ? \Carbon\Carbon::parse($row->data_vencimento)->format('d/m/Y') : '-' }}
            @endinteract

            {{-- Formatação da Data de Pagamento --}}
            @interact('column_data_pagamento', $row)
                {{ $row->data_pagamento ? \Carbon\Carbon::parse($row->data_pagamento)->format('d/m/Y') : 'Pendente' }}
            @endinteract

            {{-- Conversão da Situação (P, A, C) --}}
            @interact('column_situacao', $row)
                @php
                    $status = [
                        'P' => ['label' => 'Pago', 'color' => 'green'],
                        'A' => ['label' => 'Aguardando', 'color' => 'yellow'],
                        'C' => ['label' => 'Cancelado', 'color' => 'red'],
                    ][$row->situacao] ?? ['label' => $row->situacao, 'color' => 'gray'];
                @endphp

                <x-badge :text="$status['label']" :color="$status['color']" light />
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

</div>
