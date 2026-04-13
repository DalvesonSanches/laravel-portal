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
       'usuarios_id',
       'usuario_lotacao',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'data_ocorrencia'            =>'datetime',
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
     //relacionamento belongsTo (um inner join automatico)
    /**
         * Parâmetros:
         * 1. O Model de destino
         * 2. A coluna FK que está na tabela 'solicitacoes' (ex: empresa_fk)
         * 3. A coluna PK que está na tabela de destino (ex: cod_empresa)
     */
    public function TipoOcorrencias()//tipo da ocorrencia
    {
        return $this->belongsTo(TipoOcorrencias::class, 'tipo_ocorrencias_id', 'id');
    }
}