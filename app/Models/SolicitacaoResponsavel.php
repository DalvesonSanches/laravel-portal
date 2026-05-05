<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoResponsavel extends Model
{
    // Nome da tabela com schema (PostgreSQL)
    protected $table = 'sistec.solicitacaos_responsaveis';

    //Chave primária
    protected $primaryKey = 'id';

    //Tipo da chave primária
    protected $keyType = 'int';

    //Auto incremento
    public $incrementing = true;

    //Timestamps
    public $timestamps = true;

    //Campos que podem ser preenchidos em massa
    protected $fillable = [
        'solicitacaos_id',
        'tipo_solicitante_id',
        //'nome',
        'cpf',
        //'telefone',
        //'email',
    ];

    // Casts (opcional, mas recomendado)
    protected $casts = [
        'solicitacaos_id'       => 'integer',
        'tipo_solicitante_id'  => 'integer',
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

    public function tipoSolicitante()//tipo de solicitante
    {
        return $this->belongsTo(TipoSolicitante::class, 'tipo_solicitante_id', 'id');
    }

    public function user()//usuarios
    {
        return $this->belongsTo(User::class, 'cpf', 'cpf');
    }

    protected $appends = [
        'tipo',
        'nome',
        'telefone',
        'email',
    ];

    public function getTipoAttribute(): ?string
    {
        return $this->tipoSolicitante?->tipo;
    }

    public function getNomeAttribute(): ?string
    {
        return $this->user?->name;
    }

    public function getTelefoneAttribute(): ?string
    {
        return $this->user?->telefone;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

}
