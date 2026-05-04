<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\Attributes\On; // Importante para o listener do modal O atributo #[On] serve exclusivamente para ouvir (escutar) eventos

class TaxasCreateSuspensao extends Component
{

    public ?int $solicitacaoId = null;//id que vem como parametro
    public bool $open = false;//boleano controlar abertura do modal

    // Este atributo faz o componente "ouvir" o evento disparado pelo botão (parte 1)
    #[On('abrir-taxas-create-suspensao')]
    public function abrirModalTaxaSuspensao($solicitacaoId)
    {
        $this->resetErrorBag();//// 1. Limpa erros de validação anteriores
        //$this->reset(['tipo_anexo_id', 'observacoes', 'arquivo_upload']);//// 2. Limpa os campos para um novo registro

        $this->solicitacaoId = $solicitacaoId;//// 3. Seta o ID da solicitação pai recebido pelo evento

        $this->open = true;// // ABRE O MODAL alterano a variavel

        $this->dispatch('carregar-dados-taxas-create-suspensao');
    }

     // Este atributo faz o componente "ouvir" o evento disparado pela função acima (parte2)
    #[On('carregar-dados-taxas-create-suspensao')]
    public function carregarDadosCreateSuspensao()//aqui carregar os dados da taxa antes de salvar
    {

    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.taxas-create-suspensao');
    }
}
