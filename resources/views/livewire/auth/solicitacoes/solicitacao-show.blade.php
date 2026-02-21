<div class="py-5">
    <x-slot:title>
        Protocolo: {{ $solicitacao->num_protocolo }}
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Protocolo: {{ $solicitacao->num_protocolo }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">

        {{-- ✅ CARD RESUMO --}}
        <x-card title="Resumo da Solicitação">
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
                color="secondary"
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <span class="text-sm text-gray-500">CPF/CNPJ:</span>
                    <p class="font-semibold text-lg">
                        {{ $solicitacao->empresa_cpf_cnpj ?? '-' }}
                    </p>
                </div>

                <div>
                    <span class="text-sm text-gray-500">Razão SOcial:</span>
                    <p class="font-semibold">
                        {{ $solicitacao->empresa_razao_social ?? '-' }}
                    </p>
                </div>

                <div>
                    <span class="text-sm text-gray-500">Status</span>
                    <p>
                        <x-badge color="blue">
                            {{ $solicitacao->status_label ?? '-' }}
                        </x-badge>
                    </p>
                </div>

                <div>
                    {{--campos usando belongto--}}
                    <span class="text-sm text-gray-500">Informações do local</span>
                    <p>
                        <strong>Local de Atendimento:</strong> 
                        {{ $solicitacao->LocalAtendimentos->local ?? '-' }}
                    </p>
                    
                    <p>
                        <strong>Endereço do Local:</strong> 
                        {{ $solicitacao->LocalAtendimentos->local_endereco??'-' }}
                    </p>
                     <p>
                        <strong>Unidade Fiscalização:</strong> 
                        {{ $solicitacao->UnidadeVistoriantes->descricao??'-' }}
                    </p>
                    <p>
                        <strong>Endereço da Unidade Fiscalização:</strong> 
                        {{ $solicitacao->UnidadeVistoriantes->obm_endereco??'-' }}
                    </p>
                    <p>
                        <strong>Serviço:</strong> 
                        {{ $solicitacao->Servico->nome??'-' }} - {{ $solicitacao->ServicoSubtipo->tipo??'-' }}
                    </p>
                    <p>
                        <strong>Descrição do Serviço:</strong> 
                        {{ $solicitacao->Servico->descricao??'-' }}
                    </p>

                    <p>
                        <strong>Natureza Juridica:</strong> 
                        {{ $solicitacao->NaturezaJuridicas->codigo ?? '-' }} - {{ $solicitacao->NaturezaJuridicas->descricao ?? '-' }}
                    </p>

                    <p>
                        <strong>Altura Edificação:</strong> 
                        {{ $solicitacao->tipoAltura->nome ?? '-' }}
                    </p>

                     <p>
                        <strong>Carga de incêndio:</strong> 
                        {{ $solicitacao->tipoCarga->nome ?? '-' }}
                    </p>

                    <p>
                        <strong>Classe ocupação:</strong> 
                        {{ $solicitacao->tipoOcupacao->nome ?? '-' }}
                    </p>
                   
                    
                </div>

                <div>
                    <span class="text-sm text-gray-500">Endereço:</span>
                    <p class="font-semibold">
                        {{ $solicitacao->endereco_logradouro ?? '-' }},  {{ $solicitacao->endereco_numero ?? '-' }} {{ $solicitacao->endereco_complemento ?? '-' }} {{ $solicitacao->endereco_bairro ?? '-' }} {{ $solicitacao->endereco_municipio ?? '-' }}-{{ $solicitacao->endereco_estado ?? '-' }}
                    </p>
                </div>

                <div>
                    <span class="text-sm text-gray-500">Responsável</span>
                    <p class="font-semibold">
                        {{ $solicitacao->responsavel ?? '-' }}
                    </p>
                </div>

            </div>

            {{--abas--}}
            <x-tab selected="Andamento">
                <x-tab.items tab="Andamento">
                    <x-slot:left>
                        <x-icon name="document-text" class="w-5 h-5" />
                    </x-slot:left>
                    {{--componente livewire de ocorrencias com o id da solicitacao como parametro--}}
                    <livewire:auth.solicitacoes.ocorrencias-index 
                        :solicitacaoId="$solicitacao->id"
                        wire:key="ocorrencias-{{ $solicitacao->id }}"
                    />

                </x-tab.items>

                <x-tab.items tab="Taxas">
                    <x-slot:left>
                        <x-icon name="clock" class="w-5 h-5" />
                    </x-slot:left>
                    Andamento
                </x-tab.items>

            </x-tab>
        </x-card>

    </div>
</div>
