<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session; // garante o filtro e a paginação ao clicar no botao voltar do show
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitacao;
use TallStackUi\Traits\Interactions;//dialogos
use Illuminate\Support\Str;//uso para susbtituir o cpf por asteriscos

#[Layout('layouts.auth')]
class MeusProtocolos extends Component
{
    use WithPagination;
    use Interactions;
    // 🔹 Salva a quantidade na sessão do servidor
    #[Session]
    public int $quantity = 10;
    // 🔹 Salva o termo de busca na sessão do servidor
    #[Session]
    public ?string $search = null;
    // 🔹 ESSENCIAL: O Livewire 3 guarda a página nesta propriedade interna.
    // Ao colocar #[Session] nela, a página atual (ex: 6) será lembrada!
    #[Session]
    public $paginators = []; 

    public array $headers = [];

    public function mount()
    {
        // Headers são públicos, então o Livewire mantém o estado deles
        $this->headers = [
            ['index' => 'num_protocolo', 'label' => 'Protocolo'],
            ['index' => 'status', 'label' => 'Status'],
            ['index' => 'empresa_razao_social', 'label' => 'Empresa'],
            ['index' => 'empresa_cpf_cnpj', 'label' => 'CPF/CNPJ'],
            ['index' => 'endereco', 'label' => 'Endereço'],
            ['index' => 'nome_servico', 'label' => 'Serviço'],
            ['index' => 'action', 'label' => 'Ações'],
        ];
    }

    /**
     * 🔎 Centralizamos o mapeamento da busca em um método privado.
     * Isso evita que os dados se percam entre os requests do Livewire.
     */
    private function getSearchableFields()
    {
        return [
            'num_protocolo'        => 'solicitacaos.num_protocolo',
            'empresa_razao_social' => 'solicitacaos.empresa_razao_social',
            'empresa_cpf_cnpj'     => 'solicitacaos.empresa_cpf_cnpj',
            'nome_servico'         => ['servicos.nome', 'servicos_subtipos.tipo'],
            'endereco'             => [
                'solicitacaos.endereco_logradouro',
                'solicitacaos.endereco_bairro',
                'solicitacaos.endereco_municipio',
                'solicitacaos.endereco_estado',
            ],
            'status' => DB::raw("
                case solicitacaos.status
                    when 'AA' then 'Aguardando Atendimento'
                    when 'EA' then 'Em Atendimento'
                    when 'AP' then 'Aguardando Pagamento'
                    when 'E'  then 'Encerrado'
                    when 'AE' then 'Aguardando Envio'
                    when 'C'  then 'Cancelado'
                    else solicitacaos.status
                end
            "),
        ];
    }

    //reset na busca
    public function updatingSearch()
    {
        $this->resetPage();
    }

    //abri a solicitacao
    public function visualizar(string $autenticidade)
    {
        // A chave deve ser 'autenticidade' para casar com {autenticidade} da sua URI
        return $this->redirectRoute('solicitacoes.show', ['autenticidade' => $autenticidade], navigate: true);
    }

    // alterar a solicitacao
    public function editar(int $id)
    {
        return $this->redirectRoute('dashboard', ['id' => $id], navigate: true);
    }


    // 1. Método de confirmação
    public function confirmarExclusao(int $id): void
    {
        $this->dialog()
            ->question('Apagar esta solicitação', 'Tem certeza que deseja excluir permanentemente esta solicitação?')
            // Passamos o $id como parâmetro para o método 'delete'
            ->confirm('Sim, excluir', 'delete', $id)
            ->cancel('Cancelar')
            ->send();
    }

    // 2. Método de exclusão (agora recebe o ID)
    public function delete(int $id): void
    {
        try {
           
            //1º verificar se tem taxa
            //2º se tiver taxa paga nao deleta
            //3º se nao tiver taxa paga ou se nao tiver taxa pode ser isento, dai verifica se tem relatorio criado
            //4º se tiver relatorio criado não deleta
            //5º se nao tiver relatorio e nao tiver taxa entao deleta

            $this->toast()->success('Sucesso', 'Solicitação de serviço removidade com sucesso!')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Falha ao remover a solicitação de serviço.')->send();
        }
    }


    public function render()
    {
        $cpfUsuario = Auth::user()?->cpf;

        $rows = Solicitacao::query()
            ->select([
                'solicitacaos.id',
                'solicitacaos.data_solicitacao',
                'solicitacaos.num_protocolo',
                DB::raw("
                    case solicitacaos.status
                        when 'AA' then 'Aguardando Atendimento'
                        when 'EA' then 'Em Atendimento'
                        when 'AP' then 'Aguardando Pagamento'
                        when 'E'  then 'Encerrado'
                        when 'AE' then 'Aguardando Envio'
                        when 'C'  then 'Cancelado'
                        else solicitacaos.status
                    end as status
                "),
                'solicitacaos.empresa_razao_social',
                'solicitacaos.empresa_cpf_cnpj',
                'solicitacaos.autenticidade',
                DB::raw("
                    trim(
                        solicitacaos.endereco_logradouro || ' ' ||
                        solicitacaos.endereco_numero ||
                        coalesce(', ' || solicitacaos.endereco_complemento, '') || ' - ' ||
                        solicitacaos.endereco_bairro || ' - ' ||
                        solicitacaos.endereco_municipio || '/' ||
                        solicitacaos.endereco_estado
                    ) as endereco
                "),
                DB::raw("
                    trim(
                        servicos.nome ||
                        coalesce(' - ' || servicos_subtipos.tipo, '')
                    ) as nome_servico
                "),
            ])
            ->join('sistec.servicos', 'solicitacaos.servicos_id', '=', 'servicos.id')
            ->leftJoin('sistec.servicos_subtipos', 'solicitacaos.servicos_subtipos_id', '=', 'servicos_subtipos.id')
            ->whereExists(function ($query) use ($cpfUsuario) {
                $query->select(DB::raw(1))
                    ->from('sistec.solicitacaos_responsaveis')
                    ->whereColumn('solicitacaos_responsaveis.solicitacaos_id', 'solicitacaos.id')
                    ->where('solicitacaos_responsaveis.cpf', $cpfUsuario);
            })
            // 🔍 Filtro de busca ajustado para chamar o método privado
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    foreach ($this->getSearchableFields() as $field) {
                        if (is_array($field)) {
                            foreach ($field as $column) {
                                $q->orWhere($column, 'ilike', "%{$this->search}%");
                            }
                        } else {
                            $q->orWhere($field, 'ilike', "%{$this->search}%");
                        }
                    }
                });
            })
            ->orderByDesc('solicitacaos.id')
            ->paginate($this->quantity);

        return view('livewire.auth.solicitacoes.meus-protocolos', compact('rows'));
    }
}
