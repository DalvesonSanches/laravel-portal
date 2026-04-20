<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnexosService
{
    //Busca a regra no banco
    public function getRegra(array $dados)
    {
        return DB::table('sistec.anexos_exigidos')
            ->where('servicos_id', $dados['servicos_id'])

            ->where(function ($q) use ($dados) {
                $q->where('natureza_juridicas_id', $dados['natureza_juridicas_id'])
                  ->orWhereNull('natureza_juridicas_id');
            })

            ->where(function ($q) use ($dados) {
                $q->where('prestador_servico', $dados['prestador_servico'])
                  ->orWhereNull('prestador_servico');
            })

            ->where(function ($q) use ($dados) {
                $q->where('tipo_solicitante_id', $dados['tipo_solicitante_id'])
                  ->orWhereNull('tipo_solicitante_id');
            })

            ->where(function ($q) use ($dados) {
                $q->where('cnpj', $dados['cnpj'])
                  ->orWhereNull('cnpj');
            })

            ->where(function ($q) use ($dados) {
                $q->where('simplificado', $dados['simplificado'])
                  ->orWhereNull('simplificado');
            })

            ->where(function ($q) use ($dados) {
                $q->where('mei', $dados['mei'])
                  ->orWhereNull('mei');
            })

            //REGRA DE ÁREA
            ->where(function ($q) use ($dados) {
                $q->where(function ($q2) use ($dados) {
                    $q2->whereNull('area_min')
                       ->orWhere('area_min', '<=', $dados['area']);
                })
                ->where(function ($q2) use ($dados) {
                    $q2->whereNull('area_max')
                       ->orWhere('area_max', '>=', $dados['area']);
                });
            })

            ->orderByDesc('natureza_juridicas_id') // prioriza regras específicas
            ->first();
    }

    //Converte string de IDs em array
    public function getIdsAnexos(?string $ids): array
    {
        return !empty($ids)
            ? explode(',', $ids)
            : [];
    }

    //Busca anexos pendentes
    public function getPendentes(int $solicitacaoId, array $ids): array
    {
        if (empty($ids)) return [];
        return DB::table('sistec.itens_tipos as a')
            ->select('a.nome')
            ->whereIn('a.id', $ids)
            ->whereNotExists(function ($query) use ($solicitacaoId) {
                $query->select(DB::raw(1))
                    ->from('sistec.solicitacaos_anexos as b')
                    ->whereColumn('b.itens_tipos_id', 'a.id')
                    ->where('b.solicitacaos_id', $solicitacaoId);
            })
            ->pluck('nome')
            ->toArray();
    }

    //Monta mensagem final
    public function montarMensagem(array $pendentes): ?string
    {
        if (empty($pendentes)) return null;
        $lista = '';
        foreach ($pendentes as $nome) {
            $lista .= ' - ' . $nome . '<br>';
        }
        return $lista;
    }

    public function getAnexosRegra(array $ids): array
    {
        if (empty($ids)) return [];
        return DB::table('sistec.itens_tipos')
            ->whereIn('id', $ids)
            ->pluck('nome', 'id') // [id => nome]
            ->toArray();
    }

    public function getAnexosEnviados(int $solicitacaoId): array
    {
        return DB::table('sistec.solicitacaos_anexos as b')
            ->join('sistec.itens_tipos as a', 'a.id', '=', 'b.itens_tipos_id')
            ->where('b.solicitacaos_id', $solicitacaoId)
            ->pluck('a.nome', 'a.id') // [id => nome]
            ->toArray();
    }

    //MÉTODO PRINCIPAL (use em qualquer lugar)
    public function executar(array $dados, int $solicitacaoId): array
    {
        $regra = $this->getRegra($dados);
        $ids = $this->getIdsAnexos($regra->id_anexos ?? null);
        // NOVO DEBUG
        $anexosRegra = $this->getAnexosRegra($ids);
        $anexosEnviados = $this->getAnexosEnviados($solicitacaoId);
        $pendentes = $this->getPendentes($solicitacaoId, $ids);
        $mensagem = $this->montarMensagem($pendentes);

        return [
            'regra' => $regra,
            'ids' => $ids,
            'anexos_regra' => $anexosRegra,
            'anexos_enviados' => $anexosEnviados,
            'pendentes' => $pendentes,
            'mensagem' => $mensagem,
        ];
    }
}
