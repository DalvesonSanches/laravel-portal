<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Form;
use Livewire\Attributes\Validate;

class FormObjectEmpresasSolicitantes extends Form
{

    #[Validate('required|min:14')]
    public string $cnpj = '';

    #[Validate('required|min:5')]
    public string $razao_social = '';

    #[Validate('required')]
    public string $natureza_juridica = '';

    #[Validate('required')]
    public string $telefone = '';

    // Adicione aqui as dezenas de outros campos...

}
