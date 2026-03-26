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

                @interact('column_action', $dowloads)
                    <div class="flex gap-2">
                        {{-- Botão de "Print/Download" --}}
                        @if($dowloads->arquivo_path)
                            <x-button.circle 
                                icon="printer" 
                                href="{{ $this->getDownloadUrl($dowloads->id, app(App\Services\MinioStorageService::class)) }}" 
                                target="_blank" 
                                color="cyan" 
                                title="Ver/Imprimir Arquivo" />
                        @endif
                    </div>
                @endinteract
            </x-table>
        </x-card>
    </div>
</div>