<form wire:submit.prevent="save" class="py-5">
    <x-slot:title>
        Editar Usuário
    </x-slot:title>

    <x-slot name="header">
        <h2 class="text-xl text-gray-500 dark:text-gray-400">
            Editar:
            <span class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                {{ $user->name }}
            </span>
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <x-card>

            <x-input label="Nome" wire:model="form.name" />
            <x-input label="Email" wire:model="form.email" />
            <x-input label="CPF" wire:model="form.cpf" x-data x-mask="999.999.999-99"/>
            <x-input label="Telefone" wire:model="form.telefone" x-data x-mask="(99) 99999-9999"/>
            <x-select.styled
                label="Perfil"
                placeholder="Selecione o perfil"
                wire:model="form.role"
                :options="[
                    ['label' => 'Usuário', 'value' => 'user'],
                    ['label' => 'Administrador', 'value' => 'admin'],
                    ['label' => 'Atendente', 'value' => 'atend'],
                ]"
            />
            
            <x-slot:footer>
                {{-- Ações --}}
                <div class="flex justify-start gap-3 mt-3">
                    <x-button
                        color="yellow"
                        icon="arrow-left"
                        href="{{ route('users.index') }}"
                    >
                        Voltar
                    </x-button>

                    <x-button
                        color="green"
                        icon="pencil"
                        type="submit"
                    >
                        Atualizar
                    </x-button>
                </div>
            </x-slot:footer>
        </x-card>
       
        <div
            wire:loading.flex
            wire:target="save"
            class="fixed inset-0 z-50 items-center justify-center bg-black/30"
        >
            <div class="bg-white p-6 rounded shadow flex items-center gap-3 dark:bg-gray-800 dark:border-gray-700">
                <!-- Adicionado dark:border-white e dark:border-t-transparent -->
                <div class="animate-spin inline-block w-9 h-9 border-2 rounded-full border-gray-700 border-t-transparent dark:border-white dark:border-t-transparent"></div>
                
                <div class="text-gray-900 dark:text-gray-100">Processando... aguarde.</div>
            </div>
        </div>

    </div>
</form>
