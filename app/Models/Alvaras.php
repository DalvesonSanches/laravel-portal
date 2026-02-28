<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alvaras extends Model
{
   //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.alvaras';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
       'num_alvara',
       'data_expedicao',
       'data_validade',
       'solicitacaos_id',
       'empresas_id',
       'relatorio_vistorias_id',
       'num_autenticacao',
       'situacao',
       'tipo_alvara',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
        'data_expedicao'             =>  'date',	
        'data_validade'              =>  'date',
    ];
    //relacionamento belongsTo (um inner join automatico)
    /**
         * Parâmetros:
         * 1. O Model de destino
         * 2. A coluna FK que está na tabela 'solicitacoes' (ex: empresa_fk)
         * 3. A coluna PK que está na tabela de destino (ex: cod_empresa)
     */
    public function solicitacao()//solicitacao
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacaos_id', 'id');
    }
}
