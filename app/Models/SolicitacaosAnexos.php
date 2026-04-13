<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaosAnexos extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.solicitacaos_anexos';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
       'arquivo_nome',
       'arquivo_mime_type',
       'arquivo_ext',
       'solicitacaos_id',
       'arquivo_size',
       'data',
       'observacao',
       'publico',
       'itens_tipos_id',
       'origem',
       'aprovado',
       'data_auditoria',
       'solicitacaos_anexos_id',
       'relatorios_itens_id',
       'relatorios_itens_id_anterior',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
        'data'                       =>  'date',	
        'data_auditoria'             =>  'datetime',
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

    public function itensTipos()//tipo s de itens
    {
        return $this->belongsTo(ItensTipos::class, 'itens_tipos_id', 'id');
    }
}
