<div class="py-5">
    {{--se a variavel readonly for true exibi os slots--}}
    @if (!$readonly)
        <x-slot:title>
            Protocolo: {{ $solicitacao->num_protocolo }}
        </x-slot:title>

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Protocolo: {{ $solicitacao->num_protocolo }}
            </h2>
        </x-slot>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">

        {{-- CARD PRINCIAPL --}}
        <x-card header="Informações do protocolo: {{ $solicitacao->num_protocolo }}" color="primary" bordered>
            <x-card>
                {{--se a variavel readonly for true exibi o botao--}}
                @if (!$readonly)
                    <x-slot:header >
                        <div class="m-4 flex gap-2">
                            {{--btn volta--}}
                            <x-button round
                                icon="arrow-left"
                                color="yellow"
                                wire:navigate
                                href="{{ route('meus-protocolos') }}"
                            >
                                Voltar
                            </x-button>
                            {{--btn protocolo--}}
                            <x-button round
                                icon="printer"
                                color="orange"
                                wire:navigate
                                href="{{ route('meus-protocolos') }}"
                            >
                                Protocolo
                            </x-button>
                            {{--btn protocolo--}}
                            <x-button round
                                icon="check"
                                color="green"
                                wire:navigate
                                href="{{ route('meus-protocolos') }}"
                            >
                                Enviar processo
                            </x-button>
                        </div>
                    </x-slot:header>
                @endif
                {{-- SHOW SOLICITAÇÃO --}}
                <div class="space-y-6 ">

                    <!-- Linha 1 -->
                    <div class="grid grid-cols-1">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Serviço solicitado:
                            </span>
                            <p class="text-2xl font-bold text-primary-700 uppercase tracking-tight">
                                {{ $solicitacao->Servico->nome ?? '-' }} {{ $solicitacao->ServicoSubtipo->tipo ?? '' }}
                            </p>
                        </div>
                    </div>

                    <!-- Linha 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Data de envio:</span>
                            <p class="text-base font-semibold ">
                                {{ $solicitacao->data_envio?->format('d/m/Y') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">CPF/CNPJ:</span>
                            <p class="text-base font-semibold">
                                {{ $solicitacao->empresa_cpf_cnpj ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Razão social:</span>
                            <p class="text-base font-semibold">
                                {{ $solicitacao->empresa_razao_social ?? '-' }}
                            </p>
                        </div>

                    </div>

                    <!-- Linha 3 -->
                    <div class="grid grid-cols-1">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Endereço:</span>
                            <p class="text-base leading-relaxed">
                                {{ $solicitacao->endereco_logradouro ?? '-' }}, {{ $solicitacao->endereco_numero ?? '-' }} 
                                {{ $solicitacao->endereco_complemento ?? '' }}, {{ $solicitacao->endereco_bairro ?? '-' }},
                                {{ $solicitacao->endereco_municipio ?? '-' }} - {{ $solicitacao->endereco_estado ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Linha 4 -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Área:</span>
                            <p class="text-sm ">
                                {{ $solicitacao->area_declarada ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Natureza Juridica:</span>
                            <p class="text-sm">
                                {{ $solicitacao->NaturezaJuridicas->codigo ?? '-' }} - {{ $solicitacao->NaturezaJuridicas->descricao ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Pavimentos:</span>
                            <p class="text-sm">
                                {{ $solicitacao->qtd_pavimentos ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Isento:</span>
                            <p class="text-sm">
                                {{ $solicitacao->isento ? 'Sim' : 'Não' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">campo:</span>
                            <p class="text-sm">
                                {{ $solicitacao->campo ?? '-' }}
                            </p>
                        </div>

                    </div>

                    <!-- Linha 5 -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                Tipo de processo:
                            </span>
                            <p class="text-sm">
                                {{ $solicitacao->tipo == 'S' ? 'Simplificado' : 'Técnico' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                Unidade de fiscalização:
                            </span>
                            <p class="text-sm">
                                {{ $solicitacao->UnidadeVistoriantes->descricao??'-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                Status:
                            </span>
                            <p class="text-sm">
                                {{ $solicitacao->status_label ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                FK 4:
                            </span>
                            <p class="text-sm">
                                {{ $solicitacao->fk4 ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                FK 5:
                            </span>
                            <p class="text-sm">
                                {{ $solicitacao->fk5 ?? '-' }}
                            </p>
                        </div>

                    </div>

                    {{--se show ou analise de peojeto de show--}}
                    @if ($solicitacao->servicos_id == 29 || $solicitacao->servicos_subtipos_id == 15)
                        <!-- Linha 6 -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">

                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                    Nome do evento:
                                </span>
                                <p class="text-sm">
                                    {{ $solicitacao->evento_nome ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                    Nome do local do evento:
                                </span>
                                <p class="text-sm">
                                    {{ $solicitacao->evento_local ??'-' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                    Endereço do local do evento:
                                </span>
                                <p class="text-sm">
                                    {{ $solicitacao->evento_logradouro}},  {{ $solicitacao->evento_numero}} {{ $solicitacao->evento_complemento}} - {{ $solicitacao->evento_bairro}} {{ $solicitacao->evento_municipio}}/{{ $solicitacao->evento_estado}}
                                </p>
                            </div>
                        </div>

                        {{--abas--}}
                        <x-tab selected="Dias do eventos">
                            <x-tab.items tab="Dias do eventos">
                                <x-slot:left>
                                    <x-icon name="document-text" class="w-5 h-5" />
                                </x-slot:left>
                                {{--componente livewire de ocorrencias com o id da solicitacao como parametro--}}
                                <livewire:auth.solicitacoes.ocorrencias-index 
                                    :numProtocolo="$solicitacao->num_protocolo"
                                    wire:key="ocorrencias-{{ $solicitacao->num_protocolo }}"
                                />

                            </x-tab.items>

                            {{--Responsaveis evento--}}
                            <x-tab.items tab="Responsaveis do evento">
                                <x-slot:left>
                                    <x-icon name="clock" class="w-5 h-5" />
                                </x-slot:left>
                                Responsaveis
                            </x-tab.items>
                        </x-tab>
                    @endif

                </div>
            </x-card>


            {{--abas--}}
            <x-tab selected="Andamento">
                <x-tab.items tab="Andamento">
                    <x-slot:left>
                        <x-icon name="document-text" class="w-5 h-5" />
                    </x-slot:left>
                    {{--componente livewire de ocorrencias com o id da solicitacao como parametro--}}
                    <livewire:auth.solicitacoes.ocorrencias-index 
                        :numProtocolo="$solicitacao->num_protocolo"
                        wire:key="ocorrencias-{{ $solicitacao->num_protocolo }}"
                    />

                </x-tab.items>

                {{--TAXAS--}}
                <x-tab.items tab="Taxas">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    taxas
                </x-tab.items>

                {{--Anexos--}}
                <x-tab.items tab="Anexos">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    anexos
                </x-tab.items>

                {{--Relatorios--}}
                <x-tab.items tab="Relatorios">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    relatorios
                </x-tab.items>

                {{--Retornos--}}
                <x-tab.items tab="Retornos">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    retornos
                </x-tab.items>

                {{--Certificacoes--}}
                <x-tab.items tab="Certificações">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    certificações
                </x-tab.items>

                {{--somente analise de projeto--}}
                @if ($solicitacao->servicos_id == 39 || $solicitacao->servicos_id == 29)
                    {{--Aprovação de projetos--}}
                    <x-tab.items tab="Aprovação de projetos">
                        <x-slot:left>
                            <x-icon name="clock" class="w-5 h-5" />
                        </x-slot:left>
                        aprovação de projetos
                    </x-tab.items>
                @endif
            </x-tab>
        </x-card>

    </div>
</div>
