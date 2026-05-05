<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacaoResponsavel;
use TallStackUi\Traits\Interactions;
use App\Models\Solicitacao;
use App\Models\Ocorrencias;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use Illuminate\Support\Str;//uso para susbtituir o cpf por asteriscos

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
            ['index' => 'cpf', 'label' => 'CPF'],
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
            ->with([
                'tipoSolicitante:id,tipo',
                'user:id,cpf,name,telefone,email',
            ])
            ->where('solicitacaos_id', $this->solicitacaoId)
            ->orderBy('id')
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

        //informações usuario logado
        $nomeUsuario = Auth::user()->name;// 5. Busca o nome do usuário logado (Tabela Users)
        $cpfUsuario = Auth::user()->cpf;// busca o cpf do usuario
        $cpfLimpoLogado = preg_replace('/[^0-9]/', '', $cpfUsuario);// Remove qualquer pontuação caso o CPF venha com pontos/traços do banco
        $cpfFinal = Str::substr($cpfLimpoLogado, 0, 3) . '.***.***-' . Str::substr($cpfLimpoLogado, -2);// Pega os 3 primeiros e os 2 últimos

        // 6. Monta a base da descrição
        $descricaoAutomatica = '[AUTOMÁTICA DO SISTEMA] - Responsavel: ' . $responsavel->cpf . ' Removido por: ' . $nomeUsuario. ' CPF: '. $cpfFinal;

        $solicitacao = Solicitacao::findOrFail($this->solicitacaoId);// Buscamos os dados da solicitação
        // se encontrou, gera a ocorrencia
        if ($solicitacao) {
            // array com informações para criar o registro da ocorrencia
            $dataOcorrencia = ([
                'num_protocolo'         => $solicitacao->num_protocolo,
                'tipo_ocorrencias_id'   => 104,
                'data_ocorrencia'       => now(),
                'descricao'             => $descricaoAutomatica,
                'usuarios_id'           => 1,
                'usuario_lotacao'       => 'CETI',
            ]);
            Ocorrencias::create($dataOcorrencia);//gera o registro de ocorrencias
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
