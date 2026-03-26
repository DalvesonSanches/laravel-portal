<div class="py-5">
    <x-slot:title>Downloads</x-slot:title>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Downloads</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">
        <x-card>
            <div class="mb-4">
                <x-button href="{{ route('downloads.create') }}" icon="plus" color="green">Novo Download</x-button>
            </div>

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

                        <x-button.circle icon="eye" href="{{ route('downloads.show', $dowloads->id) }}" color="blue" title="Ver o registro" />
                        <x-button.circle icon="pencil" href="{{ route('downloads.edit', $dowloads->id) }}" color="yellow" title="Alterar o registro" />
                        
                        {{-- Botão de Lixeira chamando o Dialog --}}
                        <x-button.circle 
                            icon="trash" 
                            wire:click="confirmarExclusao({{ $dowloads->id }})" 
                            color="red"
                            title="Deletar o registro" 
                        />
                    </div>
                @endinteract
            </x-table>
        </x-card>
    </div>
</div>
