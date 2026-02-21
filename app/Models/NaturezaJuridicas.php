<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaturezaJuridicas extends Model
{
    //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.natureza_juridicas';

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
        'codigo',
        'descricao',
        'isento',
    ];

    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
