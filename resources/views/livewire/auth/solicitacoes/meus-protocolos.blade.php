{{-- 1. Adicione o x-data no topo do seu arquivo .blade.php --}}
<div class="py-5" x-data="{ carregandoGlobal: false }">
     {{-- 2. Adicione uma trava visual transparente que cobre a tela se estiver carregando --}}
    <div x-show="carregandoGlobal" 
         class="fixed inset-0 z-50 cursor-wait bg-white/10" 
         style="display: none;">
    </div>
    
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
            <x-table :$headers :$rows striped filter="search" paginate loading>
                {{-- quebra de linha nas colunas --}}
                @interact('column_status', $row)
                    <div class="block w-25 whitespace-normal wrap-break-word text-justify">
                        {{ $row->status }}
                    </div>
                @endinteract

                @interact('column_endereco', $row)
                    <div class="block w-60 whitespace-normal wrap-break-word text-justify">
                        {{ $row->endereco }}
                    </div>
                @endinteract

                @interact('column_empresa_razao_social', $row)
                    <div class="block w-40 whitespace-normal wrap-break-word text-justify">
                        {{ $row->empresa_razao_social }}
                    </div>
                @endinteract

                @interact('column_nome_servico', $row)
                    <div class="block w-37 whitespace-normal wrap-break-word text-justify">
                        {{ $row->nome_servico }}
                    </div>
                @endinteract
                {{-- barra de ações --}}
                @interact('column_action', $row)
                    <div class="flex items-center gap-2">
                        {{-- 1. Botão Principal: Visualizar --}}
                        <x-button.circle
                            icon="eye"
                            wire:click="visualizar('{{ $row->autenticidade }}')"
                            x-on:click="carregandoGlobal = true"
                            x-bind:disabled="carregandoGlobal"
                            color="blue"
                            title="Visualizar"
                        />

                        {{-- 2. Botão Principal: Responsáveis --}}
                        <x-button.circle
                            icon="share"
                            color="cyan"
                            wire:click="$dispatch('abrir-solicitacao-responsaveis', { solicitacaoId: {{ $row->id }} })"
                            wire:loading.attr="disabled"
                            x-bind:disabled="carregandoGlobal"
                            title="Responsáveis"
                        />

                        {{-- 3. Menu de Opções com Gatilho em Círculo --}}
                        <x-dropdown static>
                            <x-slot:action>
                                <x-button.circle 
                                    icon="ellipsis-vertical" 
                                    color="zinc"
                                    title="Ver opções"
                                    x-on:click="show = !show" {{-- Abre/Fecha o menu --}}
                                    x-bind:disabled="carregandoGlobal"
                                />
                            </x-slot:action>

                            {{-- Itens do Menu --}}
                            <x-dropdown.items 
                                icon="eye" 
                                text="Visualizar Protocolo" 
                                separator
                                wire:click="visualizar('{{ $row->autenticidade }}')"
                                x-on:click="carregandoGlobal = true"
                            />
                            
                            <x-dropdown.items 
                                icon="share" 
                                text="Ver Responsáveis" 
                                separator
                                wire:click="$dispatch('abrir-solicitacao-responsaveis', { solicitacaoId: {{ $row->id }} })"
                            />

                            <x-dropdown.items 
                                icon="pencil" 
                                text="Editar Registro" 
                                separator
                                wire:click="editar({{ $row->id }})"
                                x-on:click="carregandoGlobal = true"
                            />
                            {{--excluir--}}
                            <x-dropdown.items 
                                icon="trash" 
                                text="Excluir Solicitação" 
                                separator
                                wire:click="confirmarExclusao('{{ $row->id }}')"
                            />
                            {{--cancelar--}}
                            <x-dropdown.items 
                                icon="no-symbol" 
                                text="Cancelar a solicitação" 
                                separator
                                wire:click="confirmarExclusao('{{ $row->id }}')"
                            />
                        </x-dropdown>
                    </div>
                @endinteract





            </x-table>

        </x-card>
    </div>

    {{-- componente do modal--}}
   <livewire:auth.solicitacoes.solicitacao-responsaveis-index />

</div>
