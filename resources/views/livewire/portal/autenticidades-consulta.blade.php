<!-- 1. Esta DIV ocupa a largura total do layout para poder centralizar o que está dentro -->
<div class="flex justify-center w-full min-h-[60vh]">
    <!-- 2. Esta DIV limita o tamanho do card e impede que ele estique -->
    <div class="w-full max-w-md p-6 space-y-4">
        <x-slot:title>Autenticidade de documentos</x-slot:title>

        <x-card class="w-full" header="Consultar autenticidade" color="green" bordered>
            <form wire:submit.prevent="pesquisar" class="space-y-4">

                {{-- Seleção de Tipo usando Radio para garantir unicidade --}}
                <div class="flex flex-col gap-2">
                    <p>Selecione o tipo:</p>
                    <x-radio id="c1" label="Certificação" value="certificacao" wire:model.live="tipo" />
                    <x-radio id="c2" label="Relatório" value="relatorio" wire:model.live="tipo" />
                    <x-radio id="c3" label="Declaração de dispensa" value="dispensa" wire:model.live="tipo" />
                </div>

                {{-- Adicione o wire:key baseado no tipo selecionado --}}
                <div x-data="{
                    get maskPattern() {
                        // Se for certificação: 4 chars . 5 chars . 5 chars . 4 chars
                        if ($wire.tipo === 'certificacao') return '****.*****.*****.****';

                        // Se for o padrão UUID/Relatório
                        return '********-****-****-****-************';
                    }
                }" wire:key="mask-container-{{ $tipo }}">
                    <x-input
                        label="Código de autenticidade"
                        icon="code-bracket"
                        x-mask:dynamic="maskPattern"
                        wire:model.defer="codAutenticidade"
                    />
                </div>



                <x-button type="submit" class="w-full" color="blue" loading="pesquisar">
                    Consultar
                </x-button>

                <div class="text-center text-xs py-4 border-t border-gray-500">
                    <p>A autenticidade garante que o documento é original e não foi adulterado.</p>
                </div>
            </form>
        </x-card>

        {{-- Loading Overlay --}}
        <div wire:loading.flex wire:target="pesquisar" class="fixed inset-0 z-50 items-center justify-center bg-black/30">
            <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800">
                <div class="animate-spin w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white"></div>
                <div class="text-gray-900 dark:text-gray-100">Processando...</div>
            </div>
        </div>
    </div>
</div>

