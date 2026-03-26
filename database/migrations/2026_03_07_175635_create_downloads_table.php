<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS portal');

        Schema::create('portal.downloads', function (Blueprint $table) {

            $table->bigIncrements('id');

            // Identificação
            $table->string('titulo', 150);
            $table->text('descricao')->nullable();
            $table->text('finalidade')->nullable();

            // Arquivo (AGORA NO STORAGE)
            $table->string('arquivo_path');   // caminho do arquivo
            $table->string('nome_arquivo');
            $table->string('tipo_mime', 100);
            $table->bigInteger('tamanho_bytes');

            // Organização
            $table->string('categoria', 100)->nullable();
            $table->string('versao', 20)->nullable();

            // Controle
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->boolean('ativo')->default(true);

            // Auditoria
            $table->string('criado_por_cpf', 11)->nullable();
            $table->string('atualizado_por_cpf', 11)->nullable();

            $table->index('criado_por_cpf');
            $table->index('atualizado_por_cpf');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('CREATE INDEX idx_downloads_ativo ON portal.downloads (ativo)');
        DB::statement('CREATE INDEX idx_downloads_categoria ON portal.downloads (categoria)');
    }

    public function down(): void
    {
        Schema::dropIfExists('portal.downloads');
    }
};