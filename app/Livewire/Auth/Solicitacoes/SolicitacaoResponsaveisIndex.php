<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacaoResponsavel;
use TallStackUi\Traits\Interactions;

class SolicitacaoResponsaveisIndex extends Component
{
    use Interactions;
    public bool $show = false;
    public ?int $solicitacaoId = null;

    /** obrigatórios para x-table */
    public array $headers = []; //array limpo para o header da table
    public $rows; //linhas encontradas

    protected $listeners = [
        'abrir-solicitacao-responsaveis' => 'abrir',
        'refresh-responsaveis' => 'carregarResponsaveis' // Atualiza a query
    ];

    public function mount(): void
    {
        $this->headers = [
            ['index' => 'tipo', 'label' => 'Tipo'],
            ['index' => 'nome', 'label' => 'Nome'],
            ['index' => 'telefone', 'label' => 'Telefone'],
            ['index' => 'email', 'label' => 'E-mail'],
            ['index' => 'action', 'label' => 'Ações'],
        ];

        $this->rows = collect();
    }

    public function abrir(int $solicitacaoId): void
    {
        $this->solicitacaoId = $solicitacaoId;
        $this->show = true;

        $this->carregarResponsaveis();
    }

    public function fechar(): void
    {
        $this->reset(['show', 'solicitacaoId']);
        $this->rows = collect();
    }

    public function carregarResponsaveis(): void
    {
        $this->rows = SolicitacaoResponsavel::query()
            ->select([
                'solicitacaos_responsaveis.id',
                'tipo_solicitante.tipo',
                'solicitacaos_responsaveis.nome',
                'solicitacaos_responsaveis.cpf',
                'solicitacaos_responsaveis.telefone',
                'solicitacaos_responsaveis.email',
            ])
            ->join(
                'sistec.tipo_solicitante',
                'tipo_solicitante.id',
                '=',
                'solicitacaos_responsaveis.tipo_solicitante_id'
            )
            ->where('solicitacaos_responsaveis.solicitacaos_id', $this->solicitacaoId)
            ->orderBy('solicitacaos_responsaveis.id')
            ->get();
    }

    //função para confirmar exclusao chmando função excluir caso confirmi
    public function confirmarExclusao(int $id): void
    {
        $this->dialog()
            ->question('Confirmação', 'Deseja realmente excluir este responsável?')
            ->confirm('Excluir', 'excluir', $id)
            ->cancel('Cancelar')
            ->send();
    }


    public function excluir(int $id): void
    {
        //busva os dados conforme o id da linha
        $responsavel = SolicitacaoResponsavel::find($id);
        //se nao encontrou
        if (!$responsavel) {
            $this->dialog()
                ->error('Erro', 'Registro não encontrado.')
                ->send();
            return;
        }

        // Conta quantos responsáveis existem na solicitação
        $total = SolicitacaoResponsavel::where(
            'solicitacaos_id',
            $responsavel->solicitacaos_id
        )->count();

        // Regra de negócio
        if ($total <= 1) {
            $this->dialog()
                ->error('Atenção', 'Não é possível excluir o único responsável da solicitação.')
                ->send();
            return;
        }

        // Exclui
        $responsavel->delete();

        // Atualiza tabela
        //$this->dispatch('refresh-responsaveis');
        $this->carregarResponsaveis();//recarrega a table

        // Feedback visual
        $this->toast()
            ->success('Sucesso', 'Responsável excluído.')
            ->send();            
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-responsaveis-index');
    }
}
