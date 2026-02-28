<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Solicitacao;
use App\Policies\SolicitacaoPolicy;

class AppServiceProvider extends ServiceProvider
{
   protected $policies = [
        Solicitacao::class => SolicitacaoPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
