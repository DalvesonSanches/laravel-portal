<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TelefoneEmpresas extends Model
{
     //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.telefones_empresas';

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
        'empresas_id',	
        'ddd',	
        'telefone',
    ];

    //Casts
        protected $casts = [
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
    ];

    public function Empresas()//empresas
    {
        return $this->belongsTo(Empresas::class, 'empresas_id', 'id');
    }
}
