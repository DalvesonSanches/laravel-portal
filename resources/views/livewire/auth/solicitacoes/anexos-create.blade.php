<div x-data x-on:abrir-anexos-create.window="$modalOpen('anexos-create')"> {{--modal--}}
    <x-modal id="anexos-create" title="Adicionar Anexo" size="5xl">
        <div>
            {{-- seleção do tipo de anexo --}}
            <x-select.styled
                label="Tipo de Anexo"
                wire:model="tipo_anexo_id"
                :options="$tipoAnexo"
                placeholder="Selecione um tipo"
                searchable
            />
            {{--campo de observação--}}
            <x-textarea
                label="Observações"
                wire:model="observacoes"
                hint="Insira observações sobre este anexo"
                resize-auto
            />

            {{--arquivo para upload--}}
            <x-upload
                :label="'Arquivo'"
                wire:model="arquivo_upload"
                accept=".pdf,.doc,.docx,.zip,.jpg,.png"
                hint="Limite de 10MB"
            />
        </div>

        <x-slot name="footer">
            {{--botoes alinhado a esqueda com açoes--}}
            <div class="w-full mb-4 flex flex-col sm:flex-row gap-2">
                <x-button round
                    class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
                    icon="arrow-left" 
                    color="yellow"
                    x-on:click="$modalClose('anexos-create')"
                >
                    Voltar
                </x-button>

                {{--botao salvar com loading no botao--}}
                <x-button round
                    class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
                    icon="check" 
                    color="blue"
                    wire:click="salvar"
                    {{--:disabled="!$nome"--}}
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
