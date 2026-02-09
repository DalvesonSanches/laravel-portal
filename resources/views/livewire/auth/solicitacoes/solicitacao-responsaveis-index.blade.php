<div
    x-data
    x-on:abrir-solicitacao-responsaveis.window="
        $modalOpen('solicitacao-responsaveis-index')
    "
>
    <x-modal
        id="solicitacao-responsaveis-index"
        title="Listagem de responsaveis"
    >
    
     <x-table :$headers :$rows striped loading>
        @interact('column_action', $row)
            <div class="flex gap-2">
                <x-button.circle
                    icon="pencil"
                    wire:click="editar({{ $row->id }})"
                    color="yellow"
                />

                <x-button.circle
                    icon="trash"
                    wire:click="excluir({{ $row->id }})"
                    color="red"
                />
            </div>
        @endinteract
    </x-table>

    </x-modal>
</div>