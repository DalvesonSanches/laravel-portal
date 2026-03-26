<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Alvaras;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.portal')]

class EmpresasCredenciadasConsulta extends Component
{
    use WithPagination;

    public int $quantity = 10;
    public ?string $search = null;
    public array $headers = [];

    public function mount(): void
    {
        $this->headers = [
            ['index' => 'sistec.solicitacaos.empresa_cpf_cnpj', 'label' => 'CPF/CNPJ'],
            ['index' => 'sistec.solicitacaos.empresa_razao_social', 'label' => 'Razão social'],
            ['index' => 'sistec.solicitacaos.empresa_nome_fantasia', 'label' => 'Nome Fantasia'],
            ['index' => 'telefone', 'label' => 'Telefone'],
            ['index' => 'sistec.alvaras.data_validade', 'label' => 'Data validade'],
            ['index' => 'sistec.alvaras.num_alvara', 'label' => 'Credenciamento'],
            ['index' => 'sistec.servicos_subtipos.tipo', 'label' => 'Tipo'],
        ];
    }


    // reseta a busca
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

    $rows = Alvaras::query()
        ->select([
            'sistec.alvaras.id',
            'sistec.solicitacaos.empresa_cpf_cnpj as cpf_cnpj',
            'sistec.solicitacaos.empresa_razao_social as razao_social',
            'sistec.solicitacaos.empresa_nome_fantasia as nome_fantasia',
            DB::raw("STRING_AGG(sistec.telefones_empresas.ddd || ' ' || sistec.telefones_empresas.telefone, ' / ') as telefone"),
            'sistec.alvaras.data_validade as data_validade',
            'sistec.alvaras.data_expedicao as data_emissao',
            'sistec.alvaras.num_alvara as num_credenciamento',
            'sistec.solicitacaos.num_protocolo as protocolo_credenciamento',
            'sistec.servicos_subtipos.tipo as tipo_credenciamento',
        ])

        ->join('sistec.solicitacaos', 'solicitacaos.id', '=', 'alvaras.solicitacaos_id')
        ->join('sistec.servicos_subtipos', 'solicitacaos.servicos_subtipos_id', '=', 'servicos_subtipos.id')
        ->join('sistec.telefones_empresas', 'alvaras.empresas_id', '=', 'telefones_empresas.empresas_id')

        ->where('solicitacaos.servicos_id', 47)
        ->where('alvaras.situacao', 'A')

        ->when($this->search, function ($query) {
            $search = "%{$this->search}%";
            $query->where(function($q) use ($search) {
                $q->whereRaw("unaccent(sistec.solicitacaos.empresa_cpf_cnpj) ILIKE unaccent(?)", [$search])
                ->orWhereRaw("unaccent(sistec.solicitacaos.empresa_razao_social) ILIKE unaccent(?)", [$search])
                ->orWhereRaw("unaccent(sistec.solicitacaos.empresa_nome_fantasia) ILIKE unaccent(?)", [$search])
                ->orWhereRaw("unaccent(sistec.servicos_subtipos.tipo) ILIKE unaccent(?)", [$search]);
            });
        })

        ->groupBy([
            'sistec.alvaras.id',
            'sistec.solicitacaos.empresa_cpf_cnpj',
            'sistec.solicitacaos.empresa_razao_social',
            'sistec.solicitacaos.empresa_nome_fantasia',
            'sistec.solicitacaos.num_protocolo',
            'sistec.servicos_subtipos.tipo',
        ])

        ->paginate($this->quantity);

        return view('livewire.portal.empresas-credenciadas-consulta', ['rows' => $rows]);
    }
}
