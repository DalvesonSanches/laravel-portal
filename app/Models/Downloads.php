<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    //Nome da tabela com schema (PostgreSQL)
    protected $table = 'portal.downloads';
    //Chave primária
    protected $primaryKey = 'id';
    //Tipo da chave primária
    protected $keyType = 'int';
    //Timestamps
    public $timestamps = true;
    //Campos que podem ser preenchidos em massa
    protected $fillable = [
        'titulo',
        'descricao',
        'finalidade',
        'arquivo_path',
        'nome_arquivo',
        'tipo_mime',
        'tamanho_bytes',
        'categoria',
        'versao',
        'downloads_count',
        'ativo',
        'criado_por_cpf',
        'atualizado_por_cpf',
    ];
    //Casts (muito importantes no PostgreSQL)
    protected $casts = [
        'deleted_at'                 =>'datetime',
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];
}
