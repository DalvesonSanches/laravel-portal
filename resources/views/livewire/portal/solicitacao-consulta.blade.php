<div class="flex justify-center">
    <x-slot:title>
        Acesso ao portral de serviços técnicos
    </x-slot:title>

    <x-card class="w-full max-w-md p-6 space-y-4 " header="Consultar protocolo" color="green" bordered>
        {{-- login --}}
        <p class="text-center text-sm ">
            Realizar login?
            <a
                href="{{ route('login') }}"
                class="text-blue-600 hover:underline"
                wire:navigate
            >
                CLique aqui
            </a>
        </p>

        <form wire:submit.prevent="pesquisar" class="space-y-4">

            {{-- cpfCNPJ com mascara dinamica no mesmo campo--}}
            <x-input
                label="CPF ou CNPJ"
                icon="code-bracket"
                placeholder="000.000.000-00 ou 00.000.000/0000-00"
                x-data
                x-mask:dynamic="$input.replace(/\D/g, '').length <= 11 ? '999.999.999-99' : '99.999.999/9999-99'"
                wire:model.defer="cpfCnpj"
            />


            {{-- protocolo --}}
            <x-input
                label="Protocolo"
                icon="magnifying-glass"
                placeholder="000000/0000"
                hint="informe o número de protocolo"
                x-data
                x-mask="999999/9999"
                wire:model.defer="numProtocolo"
            />

            {{-- Botão consultar --}}
            <x-button
                type="submit"
                class="w-full"
                color="blue"
            >
                Consultar
            </x-button>

            <!-- LINHA FINAL -->
            <div class="text-center text-xs py-4 border-t border-gray-500">
                <div>
                   Usando esta opção você terá acesso apenas a consulta das informações, nao sendo possivel
                   imprimir, gerar ou consultar documentos desse protocolo, caso deseje esse tipo de interação
                   utilize a opção realizar login.
                </div>
            </div>
                
        </form>
    </x-card>

    <div
        wire:loading.flex
        wire:target="pesquisar"
        class="fixed inset-0 z-50 items-center justify-center bg-black/30"
    >
        <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
            <!-- Adicionado dark:border-white e dark:border-t-transparent -->
            <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
            
            <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
        </div>
    </div>

</div>
