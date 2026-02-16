<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitacaoResponsavel;
use App\Models\TipoSolicitante;
use TallStackUi\Traits\Interactions;

class SolicitacaoResponsaveisCreate extends Component
{
    use Interactions;

    public ?int $solicitacaoId = null; //id
    public bool $modalCreate = false;
    public array $tipoSolicitante = []; //array para o select

    // Campos do formulário
    public $nome, $cpf, $telefone, $email, $tipo_solicitante_id;

    protected $listeners = [
        'abrir-criacao-responsavel' => 'abrir'
    ];

    //array para o select
    public function mount(): void
    {
        $this->tipoSolicitante = TipoSolicitante::query()
            ->orderBy('tipo')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->tipo,
                'value' => $item->id,
            ])
            ->toArray();
    }

    public function abrir(int $solicitacaoId): void
    {
        $this->resetForm();
        $this->solicitacaoId = $solicitacaoId;
        $this->modalCreate = true;
    }

    //buscar por cpf
    public function buscarCpf(): void
    {
        // Remove máscara
        $cpfLimpo = preg_replace('/\D/', '', $this->cpf);

        if (empty($cpfLimpo)) {
            $this->addError('cpf', 'Informe um CPF.');
            return;
        }

        if (strlen($cpfLimpo) < 11) {
            $this->addError('cpf', 'CPF incompleto.');
            return;
        }
        else{
            // busca na tabela Users
            $usuario = User::where('cpf', $cpfLimpo)->first();
            //se encontrou usuario preenche
            if ($usuario) {
                $this->nome = $usuario->name;
                $this->email = $usuario->email;
                $this->telefone = $usuario->telefone ?? 'Não informado';
            } else {//se nao da alerta e limpa campos
                $this->dialog()
                ->warning('Atenção', 'Usuário não encontrado com este CPF.')
                ->send();
                $this->reset(['nome', 'email', 'telefone']);
            }
        }
    }

    public function salvar(): void
    {
        // Remove máscara
        $cpfLimpo = preg_replace('/\D/', '', $this->cpf);

         if (!$this->nome) {
            $this->dialog()
            ->error('Atenção', 'Busque um usuário antes de salvar..')
            ->send();
            return;
        }

        if (!$this->tipo_solicitante_id) {
            $this->dialog()
            ->error('Atenção', 'Selecione o tipo de solicitante.')
            ->send();
            return;
        }

        //Salva o solicitante responsavel
        SolicitacaoResponsavel::create([
            'solicitacaos_id' => $this->solicitacaoId,
            'nome' => $this->nome,
            'cpf' => $cpfLimpo,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'tipo_solicitante_id' => $this->tipo_solicitante_id, // Ajuste conforme sua lógica
        ]);

        // Toast PRIMEIRO
        $this->toast()
            ->success('Sucesso', 'Responsável adicionado!')
            ->send();

        // Depois fecha modal
        $this->modalCreate = false;
        $this->resetForm();

        // Atualiza table
        $this->dispatch('refresh-responsaveis');
    }

    public function resetForm()
    {
        $this->reset(['nome', 'cpf', 'telefone', 'email']);
    }

    public function render()
    {
        return view('livewire.auth.solicitacoes.solicitacao-responsaveis-create');
    }
}
