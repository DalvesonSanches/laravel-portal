<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitacao;

#[Layout('layouts.auth')] // layout padrão autenticado
class MeusProtocolos extends Component
{
    use WithPagination;

    public int $quantity = 10;

    // 🔹 obrigatório para o filter do x-table
    public ?string $search = null;

    public array $headers = [];

    /**
     * 🔎 Mapa de campos buscáveis
     * index do header => coluna(s) reais no banco
     */
    protected array $searchableFields = [];

    public function mount()
    {
        $this->headers = [
            ['index' => 'data_solicitacao_formatada', 'label' => 'Data'],
            ['index' => 'num_protocolo', 'label' => 'Protocolo'],
            ['index' => 'status', 'label' => 'Status'],
            ['index' => 'empresa_razao_social', 'label' => 'Empresa'],
            ['index' => 'empresa_cpf_cnpj', 'label' => 'CPF/CNPJ'],
            ['index' => 'endereco', 'label' => 'Endereço'],
            ['index' => 'nome_servico', 'label' => 'Serviço'],
            ['index' => 'action', 'label' => 'Ações'],
        ];

        /**
         * 🔎 Mapeamento real da busca
         * (somente o que aparece no header)
         */
        $this->searchableFields = [
            'num_protocolo' => 'solicitacaos.num_protocolo',

            'empresa_razao_social' => 'solicitacaos.empresa_razao_social',

            'empresa_cpf_cnpj' => 'solicitacaos.empresa_cpf_cnpj',

            'nome_servico' => [
                'servicos.nome',
                'servicos_subtipos.tipo',
            ],

            'endereco' => [
                'solicitacaos.endereco_logradouro',
                'solicitacaos.endereco_bairro',
                'solicitacaos.endereco_municipio',
                'solicitacaos.endereco_estado',
            ],

            // 📅 data exibida → busca na data real formatada
            'data_solicitacao_formatada' => DB::raw(
                "to_char(solicitacaos.data_solicitacao, 'DD/MM/YYYY')"
            ),

            // 📌 status exibido → busca no texto do CASE
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

    /**
     * 🔁 Sempre que digitar na busca, volta para a página 1
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cpfUsuario = Auth::user()?->cpf;

        $rows = Solicitacao::query()
            ->select([
                'solicitacaos.id',
                'solicitacaos.data_solicitacao',
                'solicitacaos.num_protocolo',

                // 📌 Status com descrição
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

                // 📍 Endereço
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

                // 🛠 Serviço + subtipo
                DB::raw("
                    trim(
                        servicos.nome ||
                        coalesce(' - ' || servicos_subtipos.tipo, '')
                    ) as nome_servico
                "),
            ])
            ->join(
                'sistec.solicitacaos_responsaveis',
                'solicitacaos.id',
                '=',
                'solicitacaos_responsaveis.solicitacaos_id'
            )
            ->join(
                'sistec.servicos',
                'solicitacaos.servicos_id',
                '=',
                'servicos.id'
            )
            ->leftJoin(
                'sistec.servicos_subtipos',
                'solicitacaos.servicos_subtipos_id',
                '=',
                'servicos_subtipos.id'
            )
            ->where('solicitacaos_responsaveis.cpf', $cpfUsuario)

            // 🔍 Busca limitada aos headers
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    foreach ($this->searchableFields as $field) {
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