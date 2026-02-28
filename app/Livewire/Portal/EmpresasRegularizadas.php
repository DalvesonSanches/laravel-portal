<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Alvaras;
use Carbon\Carbon;

//layout
#[Layout('layouts.portal')]

class EmpresasRegularizadas extends Component
{

    use WithPagination;

    public int $quantity = 10;

    // 🔥 necessário para filter
    public ?string $search = null;

    public array $headers = [];

     public function mount(): void
    {
        $this->headers = [
            ['index' => 'nome_fantasia', 'label' => 'Nome fantasia'],
            ['index' => 'endereco', 'label' => 'Endereço'],
            ['index' => 'data_validade', 'label' => 'Validade'],
        ];
    }

    //reset do search
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

      public function render()
    {
        $rows = Alvaras::query()
            ->with('solicitacao') // eager loading correto

            ->where('situacao', 'A') //situacao ativa

            ->whereDate('data_validade', '>=', Carbon::today()) //data hoje

            //busca usando unaccent
            ->when($this->search, function ($query) {

            $search = "%{$this->search}%";

                $query->whereHas('solicitacao', function ($q) use ($search) {

                    $q->whereRaw(
                        "unaccent(empresa_nome_fantasia) ILIKE unaccent(?)",
                        [$search]
                    )
                    ->orWhereRaw(
                        "unaccent(empresa_razao_social) ILIKE unaccent(?)",
                        [$search]
                    )
                    ->orWhereRaw(
                        "unaccent(empresa_cpf_cnpj) ILIKE unaccent(?)",
                        [$search]
                    )
                    ->orWhereRaw(
                        "unaccent(endereco_logradouro) ILIKE unaccent(?)",
                        [$search]
                    )
                    ->orWhereRaw(
                        "unaccent(endereco_bairro) ILIKE unaccent(?)",
                        [$search]
                    );
                });
            })

            ->paginate($this->quantity); //paginação

        return view('livewire.portal.empresas-regularizadas', [
            'rows' => $rows
        ]);
    }

}
