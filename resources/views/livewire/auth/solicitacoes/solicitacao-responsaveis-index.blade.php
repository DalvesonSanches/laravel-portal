<div x-data x-on:abrir-solicitacao-responsaveis.window="$modalOpen('solicitacao-responsaveis-index')"> {{--modal--}}
    <x-modal id="solicitacao-responsaveis-index" title="Listagem de Responsáveis" size="5xl">
        <!-- Botão para abrir o formulário de novo responsavel em outro componente -->
        <div class="mb-4 justify-start flex flex-col sm:flex-row gap-2">
            <x-button round
                class="w-full sm:w-auto justify-center" {{-- Ajusta largura no mobile --}}
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
