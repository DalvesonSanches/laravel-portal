<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use App\Models\Solicitacao;
use TallStackUi\Traits\Interactions;

#[Layout('layouts.portal')]

class SolicitacaoConsulta extends Component
{
    //alerta
    use Interactions;

    //campos publicos com validação
    #[Validate('required|min:14', message: [
    'required' => 'Informe o CNPJ ou CPF do protocolo',
    'min' => 'O campo deve ter pelo menos 11 caracteres'
    ])]
    public ?string $cpfCnpj = null;
    #[Validate('required|min:11', message: [
    'required' => 'Informe o número do protocolo da solicitação',
    'min' => 'O campo deve ter pelo menos 11 caracteres'
    ])]
    public ?string $numProtocolo = null;

    public function pesquisar()//função pesquisar
    {
        // EXECUTA as validações dos atributos #[Validate]
        $this->validate();

        // remove máscara
        //$cpfCnpj = preg_replace('/\D/', '', $this->cpfCnpj);
        $cpfCnpj = $this->cpfCnpj;

        //realiza a busca com o model solicitacao
        $solicitacao = Solicitacao::where('num_protocolo', $this->numProtocolo)
            ->where('empresa_cpf_cnpj', $cpfCnpj)
            ->first();

        //se nao encontrou a solicitacao
        if (!$solicitacao) {

            $this->dialog()->error('Erro', 'Protocolo não encontrado.')->send();
            return;
        }

        //se encontrou redireciona enviando o autenticidade como paramentro
        return redirect()->route(
            'solicitacoes.show.public',
            $solicitacao->autenticidade
        );
    }

    public function render()
    {
        return view('livewire.portal.solicitacao-consulta');
    }
}
