<div class="py-5">
    <x-slot:title>
        Nova Solicitação
    </x-slot:title>

    <!-- HEADER -->
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Nova Solicitação') }}
        </h2>
    </x-slot>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>
            <!-- Vinculamos o step ao estado do Livewire -->
            <x-step wire:model="step">
                <x-step.items step="1" title="Início" description="Dados Pessoais">
                    <div class="mt-4 space-y-4">
                        <x-input label="Nome Completo" wire:model="name" />
                    </div>

                    <x-select.styled
                        label="Empreendimento fiscalizado é o mesmo solicitante?"
                        :options="[['label' => 'Sim', 'value' => 'sim'], ['label' => 'Não', 'value' => 'nao']]"
                        select="label:label|value:value"
                        wire:model.live="is_same_owner"
                    />

                    @if($is_same_owner === 'nao')
                        {{-- Chamada moderna do Blade Component --}}
                        {{-- O ponto indica a estrutura de pastas --}}
                        <x-solicitante-campos />
                    @endif

                </x-step.items>

                <x-step.items step="2" title="Contato" description="E-mail">
                    <div class="mt-4 space-y-4">
                        <x-input label="Seu melhor e-mail" wire:model="email" />
                    </div>
                </x-step.items>

                <x-step.items step="3" title="Finalização" description="Tudo pronto!">
                    <div class="mt-4 space-y-4">
                        <x-input label="Seu teste" wire:model="teste" />
                    </div>
                </x-step.items>

                <x-step.items step="4" title="Finalização1" description="Tudo pront1o!">
                    <div class="mt-4 space-y-4">
                        <x-input label="Seu teste1" wire:model="teste1" />
                    </div>
                </x-step.items>

                <x-step.items step="5" title="Revisão" description="Confira seus dados">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-secondary-800">
                        <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
                            Resumo do Cadastro
                        </h3>

                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="text-base text-gray-900 dark:text-white">{{ $name ?: 'Não informado' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                <dd class="text-base text-gray-900 dark:text-white">{{ $email ?: 'Não informado' }}</dd>
                            </div>
                        </dl>

                        @if($is_same_owner === 'nao' && isset($solicitanteForm))
                            <h3 class="mt-6 mb-4 text-md font-semibold text-gray-700 dark:text-gray-300">
                                Dados do Solicitante
                            </h3>

                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">CNPJ</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">{{ $solicitanteForm?->cnpj }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Razão</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">{{ $solicitanteForm?->razao_social }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Natureza Jurídica</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">{{ $solicitanteForm?->natureza_juridica }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">{{ $solicitanteForm?->telefone }}</dd>
                                </div>
                            </dl>
                        @endif
                    </div>


                    <x-errors class="mt-4" />
                </x-step.items>

            </x-step>

            <!-- Controles Manuais -->
            <div class="mt-6 flex justify-between">
                <div>
                    @if($step > 1)
                        <x-button outline 
                                wire:click="previous" 
                                wire:loading.attr="disabled"
                                icon="arrow-left">
                            Voltar
                        </x-button>
                    @endif
                </div>

                <div>
                    @if($step < 5)
                        <x-button wire:click="next" 
                                wire:loading.attr="disabled"
                                loading="next"
                                right-icon="arrow-right">
                            Próximo
                        </x-button>
                    @else
                        <x-button color="green" 
                                wire:click="save" 
                                wire:loading.attr="disabled"
                                loading="save"
                                icon="check">
                            Finalizar Cadastro
                        </x-button>
                    @endif
                </div>
            </div>


        </x-card>
      </div>
</div>
