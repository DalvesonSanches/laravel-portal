<div class="flex justify-center">

    <x-slot:title>
        Empresas regularizadas
    </x-slot:title>

    <x-card class="w-full max-w-6xl p-6">

        <x-table :$headers :$rows striped filter paginate loading>

            {{-- Nome Fantasia --}}
            @interact('column_nome_fantasia', $row)
                <div class="block w-55 whitespace-normal wrap-break-word ">
                    {{ $row->solicitacao->empresa_nome_fantasia ?? '-' }}
                </div>
            @endinteract

            {{-- Endereço --}}
            @interact('column_endereco', $row)
            <div class="block w-150 whitespace-normal wrap-break-word ">
                    {{ 
                        ($row->solicitacao->endereco_logradouro ?? '') . ', ' .
                        ($row->solicitacao->endereco_numero ?? '') . ' - ' .
                        ($row->solicitacao->endereco_bairro ?? '') . ' - ' .
                        ($row->solicitacao->endereco_municipio ?? '') . '/' .
                        ($row->solicitacao->endereco_estado ?? '')
                    }}
                </div>
            @endinteract

            {{-- Validade --}}
            @interact('column_data_validade', $row)
                <div >
                    {{ optional($row->data_validade)->format('d/m/Y') }}
                </div>
            @endinteract

        </x-table>

    </x-card>

</div>
