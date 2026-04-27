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
            ['index' => 'data_vencimento', 'label' => 'Vencimento'],
            ['index' => 'data_pagamento', 'label' => 'Pagamento'],
            ['index' => 'situacao', 'label' => 'Status'],
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
            ->where('solicitacaos_id', $this->solicitacaosId)
            ->orderBy('id', 'desc')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nosso_numero', 'ilike', "%{$this->search}%")
                    ->orWhere('valor_total', 'ilike', "%{$this->search}%") // Se for coluna simples
                    ->orWhere('data_vencimento', 'ilike', "%{$this->search}%")
                    ->orWhere('data_pagamento', 'ilike', "%{$this->search}%")
                    ->orWhere('situacao', 'ilike', "%{$this->search}%")
                    // 👇 Busca dentro do relacionamento "TiposTaxas"
                    ->orWhereHas('TiposTaxas', function ($subQuery) {
                        $subQuery->where('tipo_taxa', 'ilike', "%{$this->search}%");
                    });
                }); // <-- Faltava o ponto e vírgula aqui
            })
            ->paginate($this->quantity);

        return view('livewire.auth.solicitacoes.taxas-index', [
            'rows' => $rows
        ]);
    }
}
