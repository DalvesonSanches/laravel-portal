<div>
    {{--tabela--}}
    <x-table :$headers :$rows striped filter paginate loading>
         {{-- O nome após 'column_' deve ser EXATAMENTE o 'index' do header --}}
        @interact('column_tipo_data', $row)
            <div class="block w-55 whitespace-normal wrap-break-word">
                <span class="font-semibold">
                    {{ $row->TipoOcorrencias->tipo ?? 'Sem Tipo' }}
                </span>
                <br>
                <span class="text-sm text-gray-500">
                    em: {{ $row->data_ocorrencia ? \Carbon\Carbon::parse($row->data_ocorrencia)->format('d/m/Y') : '-' }}
                </span>
            </div>
        @endinteract


        {{-- quebra de linha descrição --}}
        @interact('column_descricao', $row)
            <div class="block w-200 whitespace-normal wrap-break-word">
                {{ $row->descricao }}
            </div>
        @endinteract
    </x-table>
</div>
