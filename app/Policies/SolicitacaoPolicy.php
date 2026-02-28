<?php

namespace App\Policies;

use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\SolicitacaoResponsavel;

class SolicitacaoPolicy
{
     //Verifica se o usuário logado pode visualizar a solicitação
    public function view(User $user, Solicitacao $solicitacao): bool
    {
        return SolicitacaoResponsavel::where('solicitacaos_id', $solicitacao->id)
            ->where('cpf', $user->cpf)
            ->exists();
    }
}
