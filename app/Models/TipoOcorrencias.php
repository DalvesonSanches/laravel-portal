<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoOcorrencias extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.tipo_ocorrencias';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
       'tipo',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
