<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoResponsavel extends Model
{
    /**
     * Nome da tabela com schema (PostgreSQL)
     */
    protected $table = 'sistec.solicitacaos_responsaveis';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Tipo da chave primária
     */
    protected $keyType = 'int';

    /**
     * Auto incremento
     */
    public $incrementing = true;

    /**
     * Timestamps
     */
    public $timestamps = true;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'solicitacaos_id',
        'tipo_solicitante_id',
        'nome',
        'cpf',
        'telefone',
        'email',
    ];

    /**
     * Casts (opcional, mas recomendado)
     */
    protected $casts = [
        'solicitacaos_id'       => 'integer',
        'tipo_solicitante_id'  => 'integer',
    ];
}
