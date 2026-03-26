<div class="py-5">
    @php
        $urlAtual = null;
        if($modo_edicao && $downloads?->arquivo_path) {
            // Chamada segura do Service para gerar o link de visualização
            $urlAtual = app(App\Services\MinioStorageService::class)->url('portal-bucket', $downloads->arquivo_path);
        }
    @endphp

    <x-slot:title>{{ $modo_edicao ? 'Editar Registro' : 'Novo Download' }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card :header="$modo_edicao ? 'Editar Registro' : 'Novo Download'" color="yellow" bordered>
            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Título" wire:model="titulo" />
                    <x-select.styled 
                        label="Categoria"
                        wire:model="categoria"
                        placeholder="Selecione uma categoria"
                        :options="['Manual', 'Formulário', 'Requerimento', 'Oficio']" 
                    />
                </div>

                <x-textarea label="Descrição" wire:model="descricao" rows="2" />
                <x-textarea label="Finalidade" wire:model="finalidade" rows="2" />

                <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-upload
                            :label="$modo_edicao ? 'Alterar arquivo' : 'Novo arquivo'"
                            wire:model="arquivo_upload"
                            accept=".pdf,.doc,.docx,.zip,.jpg,.png"
                            hint="Limite de 10MB"
                        />
                    </div>

                    <div class="flex items-center">
                        @if($modo_edicao && $urlAtual)
                            <div class="flex flex-col bg-gray-100 p-4 rounded-lg border border-dashed border-gray-300">
                                <span class="text-xs text-gray-500 font-bold mb-1">ARQUIVO ATUAL NO SISTEMA:</span>
                                <a href="{{ $urlAtual }}" target="_blank" class="text-blue-700 font-medium hover:underline flex items-center gap-2">
                                    <x-icon name="cloud-arrow-down" class="w-5 h-5" />
                                    {{ $downloads->nome_arquivo }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <x-button href="{{ route('downloads.index') }}" color="yellow">Voltar</x-button>
                    <x-button type="submit" color="blue" :loading="true" wire:target="save, arquivo_upload">
                        {{ $modo_edicao ? 'Salvar Alterações' : 'Cadastrar Download' }}
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
