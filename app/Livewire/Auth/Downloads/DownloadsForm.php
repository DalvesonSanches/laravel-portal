<?php

namespace App\Livewire\Auth\Downloads;

use Livewire\Component;
use App\Models\Downloads;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Auth;
use App\Services\MinioStorageService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.auth')]
class DownloadsForm extends Component
{
    use WithFileUploads, Interactions;

    public ?Downloads $downloads = null;
    public bool $modo_edicao = false;
    public ?string $titulo = null;
    public ?string $categoria = null;
    public ?string $descricao = null;
    public ?string $finalidade = null;
    public $arquivo_upload = null;

    public function mount(?Downloads $downloads = null)
    {
        if ($downloads && $downloads->exists) {
            $this->downloads = $downloads;
            $this->modo_edicao = true;
            $this->fill($downloads->only(['titulo', 'descricao', 'finalidade', 'categoria']));
        }
    }

    public function save(MinioStorageService $service)
    {
        // EXECUTA as validações dos atributos #[Validate]
       // $this->validate();

        $this->validate([
            'titulo' => 'required|string|min:5',
            'descricao' => 'required|string|min:5',
            'finalidade' => 'required|string|min:5',
            'categoria' => 'required|string|min:5',
            'arquivo_upload' => $this->modo_edicao ? 'nullable|file|max:10240' : 'required|file|max:10240',
        ]);

        try {
            $bucket = 'portal-bucket';
            $diretorio = 'downloads';
            
            $data = [
                'titulo' => $this->titulo,
                'descricao' => $this->descricao,
                'finalidade' => $this->finalidade,
                'categoria' => $this->categoria,
                'atualizado_por_cpf' => Auth::user()?->cpf ?? '00000000000',
            ];

            if ($this->arquivo_upload) {
                // 1. CAPTURA OS DADOS ANTES DE DELETAR O TEMPORÁRIO
                $nomeOriginal = $this->arquivo_upload->getClientOriginalName();
                $mime = $this->arquivo_upload->getMimeType();
                $tamanho = $this->arquivo_upload->getSize();

                // 2. Remove arquivo antigo do MinIO se for edição
                if ($this->modo_edicao && $this->downloads->arquivo_path) {
                    $service->excluirArquivo($bucket, $this->downloads->arquivo_path);
                }

                // 3. Envia o novo arquivo para o MinIO
                $path = $service->salvarArquivo($bucket, $diretorio, $this->arquivo_upload);

                // 4. AGORA SIM: Deleta o temporário local do disco do SAIL
                $this->arquivo_upload->delete();

                // 5. Monta o array com as variáveis capturadas no passo 1
                $data += [
                    'arquivo_path'  => $path,
                    'nome_arquivo'  => $nomeOriginal,
                    'tipo_mime'     => $mime,
                    'tamanho_bytes' => $tamanho,
                ];
            }

            if ($this->modo_edicao) {
                $this->downloads->update($data);
                $this->toast()->success('Sucesso', 'Registro atualizado!')->send();
            } else {
                $data['criado_por_cpf'] = Auth::user()?->cpf ?? '00000000000';
                Downloads::create($data);
                
                $this->toast()->success('Sucesso', 'Arquivo salvo!')->send();
                $this->reset(['titulo', 'descricao', 'finalidade', 'categoria', 'arquivo_upload']);
            }

        } catch (\Throwable $e) {
            report($e);
            // Exibe o erro real para debug se necessário
            $this->toast()->error('Erro', 'Falha: ' . $e->getMessage())->send();
        }
    }

    public function render()
    {
        return view('livewire.auth.downloads.downloads-form');
    }
}
