<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Solicitacao extends Model
{
    /**
     * Tabela com schema (PostgreSQL)
     */
    protected $table = 'sistec.solicitacaos';

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
     * Timestamps
     */
    public $timestamps = true;

    /**
     * Campos liberados para mass assignment
     */
    protected $fillable = [
        'data_solicitacao',
        'empresas_id',
        'local_atendimentos_id',
        'area_declarada',
        'unidade_vistoriantes_id',
        'num_protocolo',
        'origem',
        'status',
        'servicos_id',
        'local_entrega_alvara',
        'qtd_pavimentos',
        'documento_impresso',
        'empresa_razao_social',
        'empresa_nome_fantasia',
        'empresa_cpf_cnpj',
        'empresa_isento',
        'endereco_numero',
        'endereco_logradouro',
        'endereco_bairro',
        'endereco_referencia',
        'endereco_municipio',
        'endereco_estado',
        'endereco_complemento',
        'endereco_cep',
        'classes_glp_id',
        'natureza_juridicas_id',
        'solicitantes_id',
        'tipo',
        'perguntas',
        'prestador_servico',
        'data_envio',
        'evento_nome',
        'evento_local',
        'evento_numero',
        'evento_logradouro',
        'evento_bairro',
        'evento_referencia',
        'evento_municipio',
        'evento_estado',
        'evento_complemento',
        'evento_cep',
        'itens_tipos_alt_edificacao_id',
        'tipo_solicitante_id',
        'evento_estrutura',
        'evento_estrutura_area',
        'itens_tipos_cargas_id',
        'itens_tipos_ocupacoes_id',
        'servicos_subtipos_id',
    ];

    /**
     * Casts (muito importantes no PostgreSQL)
     */
    protected $casts = [
        'data_solicitacao'           => 'date',
        'data_envio'                 => 'datetime',
        'area_declarada'             => 'decimal:2',
        'evento_estrutura_area'      => 'decimal:2',
        'documento_impresso'         => 'boolean',
        'empresa_isento'             => 'boolean',
        'prestador_servico'          => 'boolean',
        'perguntas'                  => 'array',
        'created_at'                 => 'datetime',
        'updated_at'                 => 'datetime',
    ];

    //formata o campo data_solicitacao para dd/mm/yyyy saindo como data_solicitacao_formatada
    protected function dataSolicitacaoFormatada(): Attribute
    {
        return Attribute::get(
            fn () => $this->data_solicitacao
                ? $this->data_solicitacao->format('d/m/Y')
                : null
        );
    }


}
