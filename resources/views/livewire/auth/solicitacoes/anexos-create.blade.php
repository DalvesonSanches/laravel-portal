<div x-data x-on:abrir-anexos-create.window="$modalOpen('anexos-create')"> {{--modal--}}
    <x-modal id="anexos-create" title="Adicionar Anexo" size="5xl">
        <div>
            {{--lista de anexos pendentes--}}
            @if($anexosPendentes)
                <x-alert title="Anexos exigidos:" color="yellow">
                    {!! $anexosPendentes !!}
                    <x-slot:footer>
                        <div class="flex justify-end">
                            <x-badge md text="É de inteira responsabilidade do solicitante enviar todos os anexos exigidos em normas técnicas do Corpo de Bombeiros Militar do Amapá." color="yellow" />
                        </div>
                    </x-slot:footer>
                </x-alert>
            @endif
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
                hint="Somente arquivos .pdf,.doc,.docx,.zip,.jpg,.png (Limite de 100MB)"
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
                    wire:loading.attr="disabled"
                    wire:target="arquivo_upload, salvar"
                >
                    {{-- estado normal --}}
                    <span wire:loading.remove wire:target="arquivo_upload, salvar">
                        Salvar
                    </span>

                    {{-- durante upload --}}
                    <span wire:loading wire:target="arquivo_upload">
                        Enviando arquivo...
                    </span>

                    {{-- durante salvar --}}
                    <span wire:loading wire:target="salvar">
                        Salvando...
                    </span>
                </x-button>

            </div>
        </x-slot>
    </x-modal>
</div>
