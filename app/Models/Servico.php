<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    /**
     * Tabela com schema (PostgreSQL)
     */
    protected $table = 'sistec.servicos';

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
     * Campos liberados para mass assignment
     */
    protected $fillable = [
        'nome',
        'metodo',
        'quantidade_predefinida',
        'quantidade_descricao',
        'descricao',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'metodo'                 => 'integer',
        'quantidade_predefinida' => 'integer',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
    ];
}
