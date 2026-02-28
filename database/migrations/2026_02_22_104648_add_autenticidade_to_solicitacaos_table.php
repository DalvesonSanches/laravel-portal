<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Garantir extensão UUID
        DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto');

        // 2️⃣ Criar coluna com DEFAULT
        Schema::table('sistec.solicitacaos', function (Blueprint $table) {
            $table->uuid('autenticidade')
                  ->nullable()
                  ->default(DB::raw('gen_random_uuid()'));
        });

        // 3️⃣ Garantir preenchimento (extra segurança)
        DB::statement('
            UPDATE sistec.solicitacaos
            SET autenticidade = gen_random_uuid()
            WHERE autenticidade IS NULL
        ');

        // 4️⃣ Índice único
        Schema::table('sistec.solicitacaos', function (Blueprint $table) {
            $table->unique('autenticidade');
        });

        // 5️⃣ NOT NULL
        Schema::table('sistec.solicitacaos', function (Blueprint $table) {
            $table->uuid('autenticidade')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sistec.solicitacaos', function (Blueprint $table) {
            $table->dropUnique(['autenticidade']);
            $table->dropColumn('autenticidade');
        });
    }
};
