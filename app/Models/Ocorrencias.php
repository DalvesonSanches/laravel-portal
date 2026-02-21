<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencias extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.ocorrencias';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
       'tipo_ocorrencias_id',
       'num_protocolo',
       'data_ocorrencia',
       'descricao',
    ];
}