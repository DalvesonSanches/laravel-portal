{{-- estes campos vao ser injetados no form dinamicamente --}}
<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <x-input label="CNPJ" 
             wire:model.live="solicitanteForm.cnpj" 
             x-mask="99.999.999/9999-99" />
             
    <x-input label="Razão Social" 
             wire:model.live="solicitanteForm.razao_social" />

    <x-input label="Natureza Jurídica" 
             wire:model.live="solicitanteForm.natureza_juridica" />

    <x-input label="Telefone" 
             wire:model.live="solicitanteForm.telefone" 
             x-mask="(99) 99999-9999" />

    {{-- Seus outros 20+ campos entram aqui --}}
</div>
