<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use App\Models\Relatorios; // sistec.relatorios
use App\Services\MinioStorageService; // Certifique-se que o namespace está correto
use TallStackUi\Traits\Interactions;
use Carbon\Carbon; //datas
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]//layout

class AutenticidadesConsultaRelatorios extends Component
{

    use Interactions;

    public $relatorio;
    public $urlDocumento;

    public function mount($codigo, MinioStorageService $storageService)
    {
        $this->relatorio = Relatorios::where('numero_autenticacao', $codigo)->first();

        // 1. Validação de existência
        if (!$this->relatorio) {
            $this->dialog()->flash()->error('Erro', 'Documento não localizado.')->send();
            return $this->redirectRoute('autenticidades.consultas');
        }

        // 3. Geração da URL do MinIO
        // Caminho: diretorio/nome_do_arquivo
        $path = "relatorios/{$this->relatorio->nome_arquivo}";
        
        $this->urlDocumento = $storageService->url('sistec-bucket', $path);
    }

    public function render()
    {
        return view('livewire.portal.autenticidades-consulta-relatorios');
    }
}
