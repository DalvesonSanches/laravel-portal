<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicoSubtipo extends Model
{
    /**
     * Tabela com schema (PostgreSQL)
     */
    protected $table = 'sistec.servicos_subtipos';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Tipo da chave primária
     */
    protected $keyType = 'int';

    /**
     * Auto incremento
     */
    public $incrementing = true;

    /**
     * Nomes personalizados dos timestamps
     */
    const CREATED_AT = 'created_att';
    const UPDATED_AT = 'updated_att';

    /**
     * Timestamps habilitados
     */
    public $timestamps = true;

    /**
     * Campos liberados para mass assignment
     */
    protected $fillable = [
        'servicos_id',
        'tipo',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'servicos_id' => 'integer',
        'created_att' => 'datetime',
        'updated_att' => 'datetime',
    ];

    /**
     * Relacionamento com serviço
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servicos_id');
    }
}
