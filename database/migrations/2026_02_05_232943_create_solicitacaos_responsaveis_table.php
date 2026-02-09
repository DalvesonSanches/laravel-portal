<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Garante que o schema exista
        DB::statement('CREATE SCHEMA IF NOT EXISTS sistec');

        Schema::create('sistec.solicitacaos_responsaveis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitacaos_id');
            $table->unsignedBigInteger('tipo_solicitante_id');

            $table->string('nome');
            $table->string('cpf', 14);
            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();

            $table->timestamps();

            /*
             |-----------------------------------------
             | Índices
             |-----------------------------------------
             */
            $table->index('cpf', 'idx_solicitacaos_responsaveis_cpf');

            /*
             |-----------------------------------------
             | Foreign Keys
             |-----------------------------------------
             */
            $table->foreign('solicitacaos_id')
                  ->references('id')
                  ->on('sistec.solicitacaos')
                  ->onDelete('cascade');

            $table->foreign('tipo_solicitante_id')
                  ->references('id')
                  ->on('sistec.tipo_solicitante');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sistec.solicitacaos_responsaveis');
    }
};
