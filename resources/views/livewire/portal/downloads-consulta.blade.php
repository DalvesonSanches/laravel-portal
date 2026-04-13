<div class="py-5">
    <x-slot:title>Lista de arquivos para downloads</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">
        <x-card>

            <x-table :$headers :$rows striped filter paginate loading>
                 {{-- quebra de linha nas colunas --}}
                @interact('column_descricao', $dowloads)
                    <div class="block w-80 whitespace-normal wrap-break-word text-justify">
                        {{ $dowloads->descricao }}
                    </div>
                @endinteract

                 {{-- quebra de linha nas colunas --}}
                @interact('column_finalidade', $dowloads)
                    <div class="block w-80 whitespace-normal wrap-break-word text-justify">
                        {{ $dowloads->finalidade }}
                    </div>
                @endinteract

                {{-- Coluna de Ação com ícone de download --}}
                @interact('column_action', $dowloads)
                    <div class="flex items-center justify-center">
                        <x-button.circle icon="cloud-arrow-down" 
                                        color="blue" 
                                        wire:click="download({{ $dowloads->id }})" 
                                        wire:loading.attr="disabled"
                                        title="Baixar Arquivo" />
                    </div>
                @endinteract
            </x-table>
        </x-card>
    </div>
</div>