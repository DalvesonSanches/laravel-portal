<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use App\Models\DeclaracaoDispensas; // sistec.declaracao_dispensa
use TallStackUi\Traits\Interactions;
use App\Services\MinioStorageService; // Certifique-se que o namespace está correto
use Carbon\Carbon; //datas
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]//layout

class AutenticidadesConsultaDeclaracoes extends Component
{
    use Interactions;

    public $declaracaoDispensas;
    public $urlDocumento;

    public function mount($codigo, MinioStorageService $storageService)
    {
        $this->declaracaoDispensas = DeclaracaoDispensas::where('declaracao_autenticidade', $codigo)->first();

        // 1. Validação de existência
        if (!$this->declaracaoDispensas) {
            $this->dialog()->flash()->error('Erro', 'Documento não localizado.')->send();
            return $this->redirectRoute('autenticidades.consultas');
        }

        // 2. Validação de validade
        if (Carbon::parse($this->declaracaoDispensas->declaracao_validade)->isPast()) {
            $vencimento = Carbon::parse($this->declaracaoDispensas->declaracao_validade)->format('d/m/Y');
            $this->dialog()->flash()->warning('Expirado', "Venceu em: {$vencimento}")->send();
            return $this->redirectRoute('autenticidades.consultas');
        }

        // 3. Geração da URL do MinIO
        // Caminho: diretorio/nome_do_arquivo
        $path = "declaracoes/{$this->declaracaoDispensas->declaracao_arquivo}";
        
        $this->urlDocumento = $storageService->url('sistec-bucket', $path);
    }

    public function render()
    {
        return view('livewire.portal.autenticidades-consulta-declaracoes');
    }
}
