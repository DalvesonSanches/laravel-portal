<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSolicitante extends Model
{
    /**
     * Nome da tabela com schema (PostgreSQL)
     */
    protected $table = 'sistec.tipo_solicitante';
    
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
    public $timestamps = false;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'tipo',
    ];

}
