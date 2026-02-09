<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
         |----------------------------------------------------
         | 1️⃣ Normaliza tipo_solicitante_id nulo
         |----------------------------------------------------
         */
        DB::statement("
            UPDATE sistec.solicitacaos
            SET tipo_solicitante_id = 9
            WHERE tipo_solicitante_id IS NULL
        ");

        /*
         |----------------------------------------------------
         | 2️⃣ Popula solicitacaos_responsaveis
         |----------------------------------------------------
         */
        DB::statement("
            INSERT INTO sistec.solicitacaos_responsaveis (
                solicitacaos_id,
                tipo_solicitante_id,
                nome,
                cpf,
                telefone,
                email,
                created_at,
                updated_at
            )
            SELECT
                solicitacaos.id AS solicitacaos_id,
                solicitacaos.tipo_solicitante_id,
                solicitantes.nome,
                solicitantes.cpf,
                regexp_replace(solicitantes.telefone, '[^0-9]', '', 'g') AS telefone,
                solicitantes.email,
                solicitacaos.created_at,
                solicitacaos.updated_at
            FROM
                sistec.solicitacaos
            INNER JOIN
                sistec.solicitantes
                ON solicitacaos.solicitantes_id = solicitantes.id
            WHERE
                solicitacaos.tipo_solicitante_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        // Remove apenas os registros inseridos por esta migration
        DB::statement("
            DELETE FROM sistec.solicitacaos_responsaveis
        ");
    }
};