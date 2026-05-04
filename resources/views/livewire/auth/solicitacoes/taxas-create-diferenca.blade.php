<form wire:submit.prevent="salvar"> {{--modal--}}
    <x-modal title="Taxa referente a diferença de valor" wire="open" size="5xl">
        <div>
            {{--campo de observação--}}
            <x-textarea
                label="Observações"
                wire:model="observacoes"
                hint="Insira observações sobre este anexo"
                resize-auto
            />
        </div>

        <x-slot name="footer">
            {{--botoes alinhado a esqueda com açoes--}}
            <div class="w-full mb-4 flex flex-col sm:flex-row gap-2">
                <x-button round
                    class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
                    icon="arrow-left"
                    color="yellow"
                    wire:click="$set('open', false)" {{-- Maneira correta de fechar quando usa wire="open" --}}
                >
                    Voltar
                </x-button>

                {{--botao salvar com loading no botao--}}
                <x-button round
                    class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
                    icon="check"
                    color="blue"
                    type="submit"
                    wire:target="salvar"
                    wire:loading.attr="disabled"
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
</form>
