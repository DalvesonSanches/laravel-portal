<div x-data x-on:abrir-criacao-responsavel.window="$modalOpen('solicitacao-responsaveis-create')">
    <x-modal id="solicitacao-responsaveis-create" title="Adicionar Responsável" size="5xl">
        <div class="grid grid-cols-12 gap-4">
            <!-- Busca -->
           <div class="col-span-12">
                <div class="flex gap-4">

                    <!-- CPF -->
                    <div class="w-72"> {{-- largura fixa elegante --}}
                        <x-input 
                            label="CPF"
                            wire:model="cpf"
                            x-mask="999.999.999-99"
                        />

                        @error('cpf')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botão com loading livewire -->
                    <div class="pt-6"> {{-- alinha com o campo --}}
                        <x-button 
                            color="cyan" 
                            icon="magnifying-glass"
                            wire:click="buscarCpf"
                            loading="buscarCpf"
                        >
                            <span wire:loading.remove wire:target="buscarCpf">
                                Buscar
                            </span>

                            <span wire:loading wire:target="buscarCpf">
                                Buscando...
                            </span>
                        </x-button>

                    </div>

                    <!-- Função -->
                    <div class="flex-1">
                        <x-select.styled
                            label="Tipo de Solicitante"
                            wire:model="tipo_solicitante_id"
                            :options="$tipoSolicitante"
                            placeholder="Selecione um tipo"
                        />
                    </div>
                </div>
            </div>

            <!-- Labels (Inputs desabilitados) -->
            <div class="col-span-12">
                <x-input label="Nome" wire:model="nome" readonly disabled class="bg-gray-100" />
            </div>
            <div class="col-span-6">
                <x-input label="E-mail" wire:model="email" readonly disabled class="bg-gray-100" />
            </div>
            <div class="col-span-6">
                <x-input label="Telefone" wire:model="telefone" readonly disabled class="bg-gray-100" />
            </div>
        </div>

        <x-slot name="footer">
            {{--botoes alinhado a esqueda com açoes--}}
            <div class="w-full flex justify-start gap-x-4">
                <x-button 
                    icon="arrow-left" 
                    color="yellow"
                    x-on:click="$modalClose('solicitacao-responsaveis-create')"
                >
                    Voltar
                </x-button>

                {{--botao salvar com loading no botao--}}
                <x-button 
                    icon="check" 
                    color="blue"
                    wire:click="salvar"
                    :disabled="!$nome"
                    loading="salvar"
                >
                    <span wire:loading.remove wire:target="salvar">
                        Salvar
                    </span>

                    <span wire:loading wire:target="salvar">
                        Salvando...
                    </span>
                </x-button>

            </div>
        </x-slot>
    </x-modal>
</div>
