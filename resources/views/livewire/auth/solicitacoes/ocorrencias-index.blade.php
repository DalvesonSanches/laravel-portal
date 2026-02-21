<div>
    {{--tabela--}}
    <x-table :$headers :$rows striped filter paginate loading>
        {{-- quebra de linha tipo --}}
        @interact('column_tipo_data', $row)
            <div class="block w-55 whitespace-normal wrap-break-word py-2">
                {{ $row->tipo_data }}
            </div>
        @endinteract
        {{-- quebra de linha descrição --}}
        @interact('column_descricao', $row)
            <div class="block w-200 whitespace-normal wrap-break-word py-2">
                {{ $row->descricao }}
            </div>
        @endinteract
    </x-table>
</div>
