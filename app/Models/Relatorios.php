<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatorios extends Model
{
    //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.relatorios';

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
        'numero',	
        'numero_ocorrencia',	
        'data_inicio',	
        'data_fim',
        'nome_arquivo',	
        'numero_autenticacao',	
        'solicitacaos_id',
    ];

    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
        'data_fim'                 => 'datetime',
        'data_inicio'                 => 'datetime',
    ];

    //relacionamento belongsTo (um inner join automatico)
    /**
         * Parâmetros:
         * 1. O Model de destino
         * 2. A coluna FK que está na tabela 'solicitacoes' (ex: empresa_fk)
         * 3. A coluna PK que está na tabela de destino (ex: cod_empresa)
     */
    public function Solicitacao()//solicitacoes
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacaos_id', 'id');
    }
}
