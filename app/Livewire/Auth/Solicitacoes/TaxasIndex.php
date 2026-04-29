<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Taxas;
use App\Models\Relatorios;
use App\Models\PendenciasTaxas;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\Computed;//Com o atributo #[Computed], o Livewire gerencia o cache da informação dentro da mesma requisição. Se você chamar essa variável 10 vezes no Blade, o Livewire faz a consulta ao banco apenas uma vez
use Livewire\Attributes\On; // atributo para gerar eventos listeners O atributo #[On] serve exclusivamente para ouvir (escutar) eventos

class TaxasIndex extends Component
{

    use WithPagination;
    use Interactions;
    public int $quantity = 10;
    public ?string $search = null;// 🔥 necessário para filter
    public array $headers = [];
    public ?string $solicitacaosId = null; //id da solicitação que veio como parametro
    public ?string $solicitacaosStatus = null; //status da solicitação que veio como parametro
    public ?string $solicitacaosServicosId = null; //servicos_id da solicitação que veio como parametro
    public bool $solicitacaosIsento = false; //solicitacaosIsento da solicitação que veio como parametro
    public bool $readonly = false; // Propriedade para receber o readonly

    //Este método define o que aparece na tela enquanto a aba carrega.
    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex flex-col gap-4 p-4 w-full animate-pulse">
            <div class="flex justify-between items-center">
                <div class="h-8 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
                <div class="h-8 bg-gray-200 rounded w-1/4 dark:bg-gray-700"></div>
                <div class="h-8 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
            </div>
            <div class="space-y-3">
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
                <div class="h-10 bg-gray-100 rounded w-full dark:bg-gray-800"></div>
            </div>
        </div>
        HTML;
    }

    public function mount(?string $solicitacaosId = null, ?string $solicitacaosStatus = null, ?string $solicitacaosServicosId = null, $solicitacaosIsento = false, $readonly = false): void
    {
        $this->solicitacaosId = $solicitacaosId;
        $this->solicitacaosStatus = $solicitacaosStatus;
        $this->solicitacaosServicosId = $solicitacaosServicosId;
        $this->solicitacaosIsento = $solicitacaosIsento;
        $this->readonly = $readonly;
        $this->headers = [
            ['index' => 'tipo_taxa', 'label' => 'Tipo'],
            ['index' => 'nosso_numero', 'label' => 'Número'],
            ['index' => 'valor_total', 'label' => 'Valor (R$)'],
            ['index' => 'data_vencimento_sql', 'label' => 'Vencimento'], // Nome do Alias SQL
            ['index' => 'data_pagamento_sql', 'label' => 'Pagamento'],  // Nome do Alias SQL
            ['index' => 'situacao_sql', 'label' => 'Status'],           // Nome do Alias SQL
            ['index' => 'action', 'label' => 'Ação'],
        ];
    }

    //reset da busca
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    //usa Propriedade Computada para verificar se ja tem alguma taxa de abertura (1) e que esteja valida ou paga (A, P)
    //No seu Blade (você acessa como se fosse uma variável, mas com this->->temTaxaAbertura (retorna boleano)
    #[Computed]
    public function temTaxaAbertura()
    {
        return Taxas::where('solicitacaos_id', $this->solicitacaosId)
            ->where('tipo_taxas_id', 1)
            ->whereIn('situacao', ['A', 'P'])
            ->exists();
    }

    //usa Propriedade Computada para verificar se ja tem algum relatorios
    #[Computed]
    public function temRelatorio()
    {
        // Retorna o objeto do último relatório ou null se não existir
        return Relatorios::where('solicitacaos_id', $this->solicitacaosId)
            ->orderBy('numero', 'desc')
            ->first(); // O first() já aplica o LIMIT 1 internamente
    }

    //usa Propriedade Computada para verifica se tem pendencia de diferença de area vistoria, habite-se ou usbuilt com situação Vencido ou Cancelado
    #[Computed]
    public function temPendenciasTaxas()
    {
        // Retorna o objeto do último registro de pendencias de taxa de diferença de area, não paga
        return PendenciasTaxas::where('solicitacaos_id', $this->solicitacaosId)
            ->where('tipo_taxas_id', 2)
            ->where('pendente', true)
            ->orderBy('id', 'desc')
            ->first(); // O first() já aplica o LIMIT 1 internamente
    }

    //usa Propriedade Computada para verifica se tem taxa de pendencia de diferença de area com base no id do boleto acima
    #[Computed]
    public function temtaxaDiferencaArea()
    {
        // 1. Acessa a outra propriedade computada
        $pendencia = $this->temPendenciasTaxas;

        // 2. Verifica se a pendência existe e se possui um boletos_id
        if (!$pendencia || !$pendencia->boletos_id) {
            return null;
        }

        // 3. Busca a taxa usando o ID que veio da pendência
        return Taxas::where('id', $pendencia->boletos_id)
            ->where('solicitacaos_id', $this->solicitacaosId)
            ->first();
    }

    //usa Propriedade Computada para verifica se tem taxa de pendencia do 5 relatorio
    #[Computed]
    public function temPendencia5Relatorio()
    {
        // Retorna o objeto do último registro de pendencias de taxa de diferença de area, não paga
        return PendenciasTaxas::where('solicitacaos_id', $this->solicitacaosId)
            ->where('tipo_taxas_id', 3)
            ->where('pendente', true)
            ->orderBy('id', 'desc')
            ->first(); // O first() já aplica o LIMIT 1 internamente
    }

     //usa Propriedade Computada para verifica se tem taxa de pendencia de 5 relatorio com base no id do boleto acima
    #[Computed]
    public function temTaxa5Relatorio()
    {
        // 1. Acessa a outra propriedade computada
        $pendencia5relatorio = $this->temPendencia5Relatorio;

        // 2. Verifica se a pendência existe e se possui um boletos_id
        if (!$pendencia5relatorio || !$pendencia5relatorio->boletos_id) {
            return null;
        }

        // 3. Busca a taxa usando o ID que veio da pendência
        return Taxas::where('id', $pendencia5relatorio->boletos_id)
            ->where('solicitacaos_id', $this->solicitacaosId)
            ->first();
    }

    // usa o atributo para gerar um evento que refresh de pagina O Livewire detecta o evento e re-executa a query no render()
    #[On('refresh-taxas')]
    public function refresh(){}

    public function render()
    {
        $rows = Taxas::query()
            ->with('TiposTaxas')
            ->select('*')
            ->selectRaw("to_char(data_vencimento, 'DD/MM/YYYY') as data_vencimento_sql")
            ->selectRaw("to_char(data_pagamento, 'DD/MM/YYYY') as data_pagamento_sql")
            ->selectRaw("
                CASE
                    WHEN situacao = 'A' THEN 'Aguardando Pagamento'
                    WHEN situacao = 'P' THEN 'Pago'
                    WHEN situacao = 'V' THEN 'Vencida'
                    WHEN situacao = 'C' THEN 'Cancelado'
                    ELSE situacao
                END as situacao_sql
            ")
            ->where('solicitacaos_id', $this->solicitacaosId)
            ->when($this->search, function ($query) {
                // Aplicamos unaccent no termo de busca
                $searchTerm = "%{$this->search}%";

                $query->where(function ($q) use ($searchTerm) {
                    // Busca em colunas simples com unaccent
                    $q->whereRaw("unaccent(nosso_numero) ilike unaccent(?)", [$searchTerm])
                    ->orWhere('valor_total', 'ilike', $searchTerm);

                    // Busca no relacionamento ignorando acentos
                    $q->orWhereHas('TiposTaxas', function ($subQuery) use ($searchTerm) {
                        $subQuery->whereRaw("unaccent(tipo_taxa) ilike unaccent(?)", [$searchTerm]);
                    });

                    // Busca nas Datas (não precisa de unaccent)
                    $q->orWhereRaw("to_char(data_vencimento, 'DD/MM/YYYY') ilike ?", [$searchTerm])
                    ->orWhereRaw("to_char(data_pagamento, 'DD/MM/YYYY') ilike ?", [$searchTerm]);

                    // Busca na Situação formatada ignorando acentos
                    $q->orWhereRaw("
                        unaccent(CASE
                            WHEN situacao = 'A' THEN 'Aguardando Pagamento'
                            WHEN situacao = 'P' THEN 'Pago'
                            WHEN situacao = 'V' THEN 'Vencida'
                            WHEN situacao = 'C' THEN 'Cancelado'
                            ELSE situacao
                        END) ilike unaccent(?)", [$searchTerm]
                    );
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->quantity);

        return view('livewire.auth.solicitacoes.taxas-index', ['rows' => $rows]);
    }


}
