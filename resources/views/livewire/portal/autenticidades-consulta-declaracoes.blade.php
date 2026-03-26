<div class="py-5">
        <x-slot:title>
            Consulta de autenticidade de declaração: {{ $declaracaoDispensas->declaracao_numero }}
        </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">

        {{-- CARD PRINCIAPL --}}
        <x-card header="Informações da declaração de dispensa: {{ $declaracaoDispensas->declaracao_numero }}" color="primary" bordered>
            <x-card>
                {{-- SHOW SOLICITAÇÃO --}}
                <div class="space-y-6 ">

                    <!-- Linha 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Data de validade:</span>
                            <p class="text-base font-semibold ">
                                {{ $declaracaoDispensas->declaracao_validade?->format('d/m/Y') ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Código de autenticidade:</span>
                            <p class="text-base font-semibold ">
                                {{ $declaracaoDispensas->declaracao_autenticidade ?? '-'  }}
                            </p>
                        </div>
                    </div>

                    <!-- Linha 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Número:</span>
                            <p class="text-base font-semibold ">
                                {{ $declaracaoDispensas->declaracao_numero ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">CPF/CNPJ:</span>
                            <p class="text-base font-semibold">
                                {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_cpf_cnpj', '-') }}
                            </p>
                        </div>
                    </div>
                    <!-- Linha 3 -->
                    <div class="grid grid-cols-1">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Razão social:</span>
                            <p class="text-base font-semibold">
                                {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_razao_social', '-') }}
                            </p>
                        </div>

                    </div>

                      <!-- Linha 4 -->
                    <div class="grid grid-cols-1">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Nome Fantasia:</span>
                            <p class="text-base font-semibold">
                                 {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_nome_fantasia', '-') }}
                            </p>
                        </div>

                    </div>

                    <!-- Linha 5 -->
                    <div class="grid grid-cols-1">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase">Endereço:</span>
                            <p class="text-base leading-relaxed">
                                {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_logradouro', '-') }}, {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_numero', '-') }},
                                {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_complemento', '-') }}, {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_bairro', '-') }},
                                {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_municipio', '-') }}, {{ data_get($declaracaoDispensas->declaracao_json, 'token.passo03_estado', '-') }}
                            </p>
                        </div>
                    </div>

                    <!-- botao de download -->
                    <div class="pt-4 border-t border-gray-200 text-center">                 
                        <div class="flex gap-2 justify-center">
                            <x-button :href="$urlDocumento" target="_blank" icon="eye" color="blue">
                                Visualizar Declaração
                            </x-button>
                            
                            <x-button :href="route('autenticidades.consultas')" wire:navigate icon="magnifying-glass" outline color="yellow">
                                Nova Consulta
                            </x-button>
                        </div>
                    </div>

                </div>
            </x-card>
        </x-card>
    </div>
</div>

