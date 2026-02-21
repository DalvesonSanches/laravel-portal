<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeVistoriantes extends Model
{
    //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.unidade_vistoriantes';

    //Chave primária
    protected $primaryKey = 'id';

    //Tipo da chave primária
    protected $keyType = 'int';

    //Auto incremento
    public $incrementing = true;

    //Timestamps
    public $timestamps = true;

    //campos liberados para mass assignment
    protected $fillable = [
        'descricao',
        'sigla',
        'obm',
        'obm_endereco',
    ];

    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
