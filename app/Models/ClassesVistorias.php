<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassesVistorias extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.class_vistorias';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
    'nome',	
    'isento',		
    'cod_taxa',	
    'qtd_taxa',	
   ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
