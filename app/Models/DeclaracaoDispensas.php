<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeclaracaoDispensas extends Model
{
    use HasFactory;

    // Define o schema e o nome da tabela
    protected $table = 'sistec.declaracao_dispensa';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    // Desativa timestamps padrão do Laravel (created_at/updated_at) 
    // já que você usa 'declaracao_data_criacao'
    public $timestamps = false;

    protected $fillable = [
        'declaracao_numero',
        'declaracao_autenticidade',
        'declaracao_json',
        'declaracao_data_criacao',
        'declaracao_arquivo',
        'declaracao_validade',
    ];

    //Cast" transforma o JSON do Postgres em um Array do PHP automaticamente
    protected $casts = [
        'declaracao_json' => 'array', 
        'declaracao_data_criacao' => 'datetime',
        'declaracao_validade' => 'date',
    ];
}
