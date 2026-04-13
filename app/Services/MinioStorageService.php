<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class MinioStorageService
{
    private function getDisk(string $bucket): FilesystemAdapter
    {
        return Storage::build([
            'driver'                  => 's3',
            'key'                     => config('filesystems.disks.s3.key'),
            'secret'                  => config('filesystems.disks.s3.secret'),
            'region'                  => config('filesystems.disks.s3.region'),
            'bucket'                  => $bucket,
            'endpoint'                => config('filesystems.disks.s3.endpoint'),
            'use_path_style_endpoint' => true,
            'url'                     => config('filesystems.disks.s3.endpoint') . '/' . $bucket,
        ]);
    }

    public function salvarArquivo(string $bucket, string $diretorio, $arquivo): string
    {
        $disk = $this->getDisk($bucket);

        // Gera nome único (apenas hash + extensão)
        $nomeUnico = bin2hex(random_bytes(16)) . '.' . $arquivo->getClientOriginalExtension();
        $path = $diretorio . '/' . $nomeUnico;

        // Envia o conteúdo para o MinIO
        $disk->put($path, $arquivo->get());

        return $path;
    }

    public function excluirArquivo(string $bucket, ?string $path): bool
    {
        if (!$path) return false;
        $disk = $this->getDisk($bucket);
        return $disk->exists($path) ? $disk->delete($path) : false;
    }

    public function url(string $bucket, ?string $path): ?string
    {
        if (!$path) return null;
        return $this->getDisk($bucket)->temporaryUrl($path, now()->addMinutes(30));
    }

    public function existe(string $bucket, string $path): bool
    {
        return $this->getDisk($bucket)->exists($path);
    }

}
