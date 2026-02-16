<div x-data x-on:abrir-solicitacao-responsaveis.window="$modalOpen('solicitacao-responsaveis-index')"> {{--modal--}}
    <x-modal id="solicitacao-responsaveis-index" title="Listagem de Responsáveis" size="5xl">
        <!-- Botão para abrir o formulário de novo responsavel em outro componente -->
        <div class="flex justify-start mb-4">
            <x-button 
                icon="plus"
                color="green"
                wire:click="$dispatch('abrir-criacao-responsavel', { solicitacaoId: {{ $solicitacaoId }} })"
            >
                Novo responsavel
            </x-button>
        </div>
        {{--tabela--}}
        <x-table :$headers :$rows striped loading>
            {{--barra de açoes--}}
            @interact('column_action', $row)
                <x-button.circle icon="trash" color="red" wire:click="confirmarExclusao({{ $row->id }})" />
            @endinteract
        </x-table>
    </x-modal>

    <!-- componente de formulario de responsavel -->
    <livewire:auth.solicitacoes.solicitacao-responsaveis-create />
</div>