<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use App\Models\Alvaras; // sistec.alvaras
use App\Models\Relatorios; // sistec.relatorios
use App\Models\DeclaracaoDispensas; // sistec.declaracao_dispensa
use TallStackUi\Traits\Interactions;
use Carbon\Carbon; //datas
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]//layout

class AutenticidadesConsulta extends Component
{
    use Interactions;

    public $tipo = 'certificacao'; // padrão
    public $codAutenticidade = '';

    protected $rules = [
        'codAutenticidade' => 'required|min:5',
        'tipo' => 'required|in:certificacao,relatorio,dispensa'
    ];

    public function pesquisar()
    {
        $this->validate();

        $existe = false;
        $rota = '';

        // Lógica de busca baseada no tipo
        switch ($this->tipo) {
            case 'certificacao':
                $documento = Alvaras::where('num_autenticacao', $this->codAutenticidade)->first();
                if ($documento) {
                    // Verifica se a data de validade é menor que "hoje" (agora)
                    if (Carbon::parse($documento->data_validade)->isPast()) {
                        $this->dialog()->warning('Certificação Expirada', 'Esta certificação venceu em: ' . $documento->data_validade->format('d/m/Y'))->send();
                        return;
                    }
                    $existe = true;
                    $rota = 'autenticidades.certificacoes';
                }
                break;
            case 'relatorio':
                $existe = Relatorios::where('numero_autenticacao', $this->codAutenticidade)->exists();
                $rota = 'autenticidades.relatorios';
                break;
            case 'dispensa':
                // 1. Buscamos o registro pelo código de autenticidade
                $documento = DeclaracaoDispensas::where('declaracao_autenticidade', $this->codAutenticidade)->first();

                if ($documento) {
                    // 2. Verificamos se a data de validade é anterior a hoje (isPast)
                    // Usamos ->startOfDay() para comparar apenas a data, ignorando as horas do momento atual
                    if ($documento->declaracao_validade->isPast() && !$documento->declaracao_validade->isToday()) {
                        $this->dialog()->warning(
                            'Declaração Vencida', 
                            'Esta declaração de dispensa expirou em: ' . $documento->declaracao_validade->format('d/m/Y')
                        )->send();
                        return; // Interrompe a execução para não redirecionar
                    }

                    $existe = true;
                    $rota = 'autenticidades.declaracoes';
                } else {
                    $existe = false;
                }
                break;
        }

        if ($existe) {
            return redirect()->route($rota, ['codigo' => $this->codAutenticidade]);
        }

        //erro de nao encontrado
        $this->dialog()->error('Não encontrado', 'O código informado não consta em nossa base de dados.')->send();
    }

    public function render()
    {
        return view('livewire.portal.autenticidades-consulta');
    }
}
