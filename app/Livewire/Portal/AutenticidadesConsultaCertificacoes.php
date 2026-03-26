<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use App\Models\Alvaras;
use App\Services\MinioStorageService; // Certifique-se que o namespace está correto
use TallStackUi\Traits\Interactions;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]//layout
class AutenticidadesConsultaCertificacoes extends Component
{
    use Interactions;

    public $alvara;
    public $urlDocumento;

    public function mount($codigo, MinioStorageService $storageService)
    {
        $this->alvara = Alvaras::where('num_autenticacao', $codigo)->first();

        // 1. Validação de existência
        if (!$this->alvara) {
            $this->dialog()->flash()->error('Erro', 'Documento não localizado.')->send();
            return $this->redirectRoute('autenticidades.consultas');
        }

        // 2. Validação de validade
        if (Carbon::parse($this->alvara->data_validade)->isPast()) {
            $vencimento = Carbon::parse($this->alvara->data_validade)->format('d/m/Y');
            $this->dialog()->flash()->warning('Expirado', "Venceu em: {$vencimento}")->send();
            return $this->redirectRoute('autenticidades.consultas');
        }

        // 3. Geração da URL do MinIO
        // Caminho: diretorio/nome_do_arquivo
        $path = "certificacoes/{$this->alvara->nome_arquivo}";
        
        $this->urlDocumento = $storageService->url('sistec-bucket', $path);
    }

    public function render()
    {
        return view('livewire.portal.autenticidades-consulta-certificacoes');
    }
}