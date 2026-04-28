<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxas extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.boletos';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
    'solicitacaos_id',
    'nosso_numero',
    'valor_taxa',
    'valor_total',
    'valor_pago',
    'data_pagamento',
    'data_registro',
    'hash_dar',
    'data_vencimento',
    'tipo_taxas_id',
    'empresas_id',
    'servicos_id',
    'class_vistorias_id',
    'mes_referencia',
    'area_declarada',
    'quantidade',
    'cpf_cnpj',
    'situacao',
    'origem',
   ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
        'data_pagamento'             =>  'date',
        'data_registro'              =>  'date',
        'data_vencimento'            => 'date',
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

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'empresas_id', 'id');
    }

    public function Servico()//serviço selecionado
    {
        return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }

    public function TiposTaxas()//tipo da taxa
    {
        return $this->belongsTo(TiposTaxas::class, 'tipo_taxas_id', 'id');
    }

    public function ClassesVistorias()//classe de vistorias
    {
        return $this->belongsTo(ClassesVistorias::class, 'class_vistorias_id', 'id');
    }
}
