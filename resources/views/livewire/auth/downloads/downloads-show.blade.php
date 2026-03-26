<div class="py-5">
    @php
        $urlAtual = null;
        if($downloads?->arquivo_path) {
            // Chamada segura do Service para gerar o link de visualização
            $urlAtual = app(App\Services\MinioStorageService::class)->url('portal-bucket', $downloads->arquivo_path);
        }
    @endphp
    <x-slot:title>
        Detalhes do Download
    </x-slot:title>

    <x-slot name="header">
        <h2 class="text-xl text-gray-500 dark:text-gray-400">
            Detalhes:
            <span class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                {{ $downloads->titulo }}
            </span>
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>
            <p><strong>Titulo:</strong><br> {{ $downloads->titulo }}</p>
            <p><strong>Finalidade:</strong><br> {{ $downloads->finalidade }}</p>
            <p><strong>Categoria:</strong><br> {{ $downloads->categoria }}</p>
            <p><strong>Ativo:</strong><br> {{ $downloads->ativo }}</p>
            <p><strong>Total downloads:</strong><br> {{ $downloads->downloads_count }}</p>
            <p><strong>Versao:</strong><br> {{ $downloads->versao }}</p>
            <p><strong>Criado por</strong><br> {{ $downloads->criado_por_cpf }}</p>
            <p><strong>Atualizado por:</strong><br> {{ $downloads->atualizado_por_cpf }}</p>
            <p><strong>Descricao:</strong><br> {{ $downloads->descricao }}</p>
            @if($urlAtual)
                <p><strong>Arquivo:</strong><br> 
                    <div class="flex flex-col bg-gray-100 p-4 rounded-lg border border-dashed border-gray-300">
                        <span class="text-xs text-gray-500 font-bold mb-1">ARQUIVO ATUAL NO SISTEMA:</span>
                        <a href="{{ $urlAtual }}" target="_blank" class="text-blue-700 font-medium hover:underline flex items-center gap-2">
                            <x-icon name="cloud-arrow-down" class="w-5 h-5" />
                            {{ $downloads->nome_arquivo }}
                        </a>
                    </div>
                </p>
            @endif
            <x-slot:footer>
                {{-- Ações --}}
                <div class="flex justify-start gap-3 mt-3">
                    <x-button
                        color="yellow"
                        icon="arrow-left"
                        href="{{ route('downloads.index') }}"
                    >
                        Voltar
                    </x-button>

                    <x-button
                        color="cyan"
                        icon="pencil"
                        href="{{ route('downloads.edit', $downloads) }}"
                    >
                        Editar
                    </x-button>
                </div>
            </x-slot:footer>
        </x-card>
    </div>
</div>