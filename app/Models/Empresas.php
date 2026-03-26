<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
         //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.empresas';

    //Chave primária
    protected $primaryKey = 'id';

    //Tipo da chave primária
    protected $keyType = 'int';

    //Auto incremento
    public $incrementing = true;

    //Timestamps
    public $timestamps = true;

    //Campos liberados para mass assignment
    protected $fillable = [
        'razao_social',
        'cpf_cnpj',	
        'email',	
        'site',	
        'pessoa_contato',	
        'observacao',
        'origem',	
        'protocolo_redesim',	
        'cod_porte_redesim',	
        'isento',	
        'natureza_juridicas_id',	
        'tipo',	
        'porte',	
        'efr',	
        'situacao',	
        'motivo_situacao',	
        'situacao_especial',	
        'capital_social',
        'ultimo_atualizacao',	
        'data_situacao_especial',	
        'data_situacao',
        'abertura',	
        'em_criacao',
    ];

    //Casts
        protected $casts = [
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
    ];

    public function NaturezaJuridicas()//empresas
    {
        return $this->belongsTo(NaturezaJuridicas::class, 'natureza_juridicas_id', 'id');
    }

    public function telefones()
    {
        return $this->hasMany(TelefoneEmpresas::class, 'empresas_id', 'id');
    }
}
