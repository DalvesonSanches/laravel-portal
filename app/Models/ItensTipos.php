<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItensTipos extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.itens_tipos';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
       'nome',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
