<?php

namespace App\Livewire\Auth\Solicitacoes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Taxas;
use Illuminate\Support\Facades\Auth; //para usar os dados do usuario logado
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\On; // atributo para gerar eventos listeners O atributo #[On] serve exclusivamente para ouvir (escutar) eventos

class TaxasIndex extends Component
{

    use WithPagination;
    use Interactions;
    public int $quantity = 10;
    public ?string $search = null;// 🔥 necessário para filter
    public array $headers = [];
    public ?string $solicitacaosId = null;
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

    public function mount(?string $solicitacaosId = null, $readonly = false): void
    {
        $this->solicitacaosId = $solicitacaosId;
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

/*
    // 1. Método de confirmação
    public function confirmarExclusao(int $id): void
    {
        $this->dialog()
            ->question('Remover Anexo', 'Tem certeza que deseja excluir permanentemente este arquivo?')
            // Passamos o $id como parâmetro para o método 'delete'
            ->confirm('Sim, excluir', 'delete', $id)
            ->cancel('Cancelar')
            ->send();
    }

    // 2. Método de exclusão (agora recebe o ID)
    public function delete(int $id, MinioStorageService $service): void
    {
        try {
            //$anexo = SolicitacaosAnexos::findOrFail($id);
            $anexo = SolicitacaosAnexos::with('itensTipos', 'solicitacao')->findOrFail($id);//carrega o relacionamento automaticamente
            $nomeTipo = $anexo->itensTipos->nome ?? 'Arquivo';//nome do tipo de anexo

            $nomeUsuario = Auth::user()->name; // 5. Busca o nome do usuário logado (Tabela Users)
            $descricao = '[AUTOMÁTICA DO SISTEMA] - Anexo ' . $nomeTipo . ' removido por: ' . $nomeUsuario;//descricao do delete na ocorrencia
            $numProtocolo = $anexo->solicitacao->num_protocolo;//numero protocolo atraves do relacionamento belongto
            $bucket = 'sistec-bucket';
            $caminhoNoMinio = 'anexos/' . $anexo->arquivo_nome;

            // Tenta excluir no MinIO
            $service->excluirArquivo($bucket, $caminhoNoMinio);

            // Exclui no Banco de Dados
            $anexo->delete();

            //gera ocorrencia
            Ocorrencias::create([
                'num_protocolo'       => $numProtocolo,
                'tipo_ocorrencias_id' => 101, // exemplo: ID de exclusão
                'data_ocorrencia'     => now(),
                'descricao'           => $descricao,
                'usuarios_id'         =>  1,
                'usuario_lotacao'     => 'CETI',
            ]);

            $this->dispatch('refresh-ocorrencias');//atualizar o blade da table ocorrencias

            $this->toast()->success('Sucesso', 'Arquivo removido com sucesso!')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Erro', 'Falha ao remover arquivo.')->send();
        }
    }

    //abre o modal do create
    public function abrirModal($id)
    {
        $this->dispatch('abrir-anexos-create', solicitacaoId: $id);
    }
*/

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
