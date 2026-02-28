<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Solicitacao extends Model
{
    //Tabela com schema (PostgreSQL)
    protected $table = 'sistec.solicitacaos';

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
        'natureza_juridicas_id',
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
        'autenticidade',
    ];

    //Casts (muito importantes no PostgreSQL)
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

    //gerra uuid na coluna autenticidade
    /*
    protected static function booted()
    {
        static::creating(function ($solicitacao) {
            // Gera o UUID comum (v4) automaticamente antes de salvar
            $solicitacao->autenticidade = (string) Str::uuid();
        });
    }
    */

    //relacionamento belongsTo (um inner join automatico)
    /**
         * Parâmetros:
         * 1. O Model de destino
         * 2. A coluna FK que está na tabela 'solicitacoes' (ex: empresa_fk)
         * 3. A coluna PK que está na tabela de destino (ex: cod_empresa)
     */
    public function LocalAtendimentos()//local de atendimento
    {
        return $this->belongsTo(LocalAtendimentos::class, 'local_atendimentos_id', 'id');
    }

    public function UnidadeVistoriantes()//unidades de vistotia
    {
        return $this->belongsTo(UnidadeVistoriantes::class, 'unidade_vistoriantes_id', 'id');
    }

    public function Servico()//serviço selecionado
    {
        return $this->belongsTo(Servico::class, 'servicos_id', 'id');
    }

    public function NaturezaJuridicas()//Natureza juridica
    {
        return $this->belongsTo(NaturezaJuridicas::class, 'natureza_juridicas_id', 'id');
    }

    public function tipoAltura()// Relacionamento 1: Altura da Edificação
    {
        return $this->belongsTo(ItemTipos::class, 'itens_tipos_alt_edificacao_id', 'id');
    }

    public function tipoCarga()// Relacionamento 2: Cargas
    {
        return $this->belongsTo(ItemTipos::class, 'itens_tipos_cargas_id', 'id');
    }

    public function tipoOcupacao()// Relacionamento 3: Ocupações
    {
        return $this->belongsTo(ItemTipos::class, 'itens_tipos_ocupacoes_id', 'id');
    }

     public function ServicoSubtipo()// Subtipo do serviço
    {
        return $this->belongsTo(ServicoSubtipo::class, 'servicos_subtipos_id', 'id');
    }
    

    //formata o campo data_solicitacao para dd/mm/yyyy saindo como data_solicitacao_formatada
    protected function dataSolicitacaoFormatada(): Attribute
    {
        return Attribute::get(
            fn () => $this->data_solicitacao
                ? $this->data_solicitacao->format('d/m/Y')
                : null
        );
    }

    //formata o status para uma frase use como status_label
    protected function statusLabel(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            return match ($this->status) {
                'AA'    => 'Aguardando Atendimento',
                'EA'    => 'Em Atendimento',
                'AP'    => 'Aguardando Pagamento',
                'E'     => 'Encerrado',
                'AE'    => 'Aguardando Envio',
                'C'     => 'Cancelado',
                default => $this->status,
            };
        });
    }

}
