<div class="py-5">
    <x-slot:title>Lista de credenciamentos válidos</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-1 space-y-6">
        <x-card>

            <x-table :$headers :$rows striped filter paginate loading>
                 {{-- quebra de linha nas colunas --}}
                @interact('column_razao_social', $rows)
                    <div class="block w-80 whitespace-normal wrap-break-word text-justify">
                        {{ $rows->razao_social }}
                    </div>
                @endinteract

                 {{-- quebra de linha nas colunas --}}
                @interact('column_nome_fantasia', $rows)
                    <div class="block w-80 whitespace-normal wrap-break-word text-justify">
                        {{ $rows->nome_fantasia }}
                    </div>
                @endinteract

                 {{-- quebra de linha nas colunas --}}
                @interact('column_tipo_credenciamento', $rows)
                    <div class="block w-80 whitespace-normal wrap-break-word text-justify">
                        {{ $rows->tipo_credenciamento }}
                    </div>
                @endinteract
            </x-table>
        </x-card>
    </div>
</div>
