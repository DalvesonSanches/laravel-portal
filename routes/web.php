<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Portal\Home;
use App\Livewire\Portal\SolicitacaoConsulta;
use App\Livewire\Portal\EmpresasRegularizadas;
use App\Livewire\Portal\DownloadsConsulta;
use App\Livewire\Portal\EmpresasCredenciadasConsulta;
use App\Livewire\Portal\AutenticidadesConsulta;
use App\Livewire\Portal\AutenticidadesConsultaCertificacoes;
use App\Livewire\Portal\AutenticidadesConsultaRelatorios;
use App\Livewire\Portal\AutenticidadesConsultaDeclaracoes;

use App\Livewire\Guest\Login;
use App\Livewire\Guest\Registro;
use App\Livewire\Guest\RecuperarSenha;
use App\Livewire\Guest\RedefinirSenha;
use App\Livewire\Guest\ConfirmarSenha;
use App\Livewire\Guest\VerificarEmail;

use App\Livewire\Auth\Solicitacoes\MeusProtocolos;
use App\Livewire\Auth\Solicitacoes\SolicitacoesCreate;
use App\Livewire\Auth\Solicitacoes\SolicitacaoShow;
use App\Livewire\Auth\Dashboard;
use App\Livewire\Auth\Profile\Profile;
use App\Livewire\Auth\Users\UserIndex;
use App\Livewire\Auth\Users\UserEdit;
use App\Livewire\Auth\Users\UserShow;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Auth\Downloads\DownloadsIndex;
use App\Livewire\Auth\Downloads\DownloadsShow;
use App\Livewire\Auth\Downloads\DownloadsForm;
use App\Livewire\Auth\LogoutController;

// --- ÁREA PÚBLICA LOGIN (GUEST) ---
// Usuário logado NÃO consegue ver essas páginas
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');//login
    Route::get('/registro', Registro::class)->name('registro');//novo usuario
    Route::get('/recuperar-senha', RecuperarSenha::class)->name('password.request');//recuperar senha
    Route::get('/reset-password/{token}', RedefinirSenha::class)->name('password.reset');//redefinir senha
    Route::get('/confirm-password', ConfirmarSenha::class)->name('password.confirm');//confirmar senha
});

// --- ÁREA RESTRITA LOGADO (AUTH) ---
// Usuário deslogado NÃO consegue ver essas páginas
Route::middleware('auth')->group(function () {
    
    Route::get('/meus-protocolos', MeusProtocolos::class)->name('meus.protocolos');//blade de meus protocolos
    Route::get('/solicitacoes', SolicitacoesCreate::class)->name('solicitacoes.create');//formulario de criação da solicitacao
    Route::get('/solicitacoes/{autenticidade}', SolicitacaoShow::class)->name('solicitacoes-show');//exibição da solicitacao em abas readonly false
    Route::get('/dashboard', Dashboard::class)->name('dashboard');//dashboard apos login
    Route::get('/profile', Profile::class)->name('profile');//perfil do usuario
    Route::get('/downloads', DownloadsIndex::class)->name('downloads.index');//blade de downloads de anexos
    Route::get('/downloads/create', DownloadsForm::class)->name('downloads.create');//create do dowload
    Route::get('/downloads/{downloads}', DownloadsShow::class)->name('downloads.show');//exibir registro download
    Route::get('/downloads/{downloads}/edit', DownloadsForm::class)->name('downloads.edit');//edit do download
    
    Route::get('/users', UserIndex::class)->name('users.index');//listagem dos usuarios
    Route::get('/users/{user}', UserShow::class)->name('users.show');//exibir usuario
    Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');//edit do usuairo
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');//sair do sistema
});

// --- ÁREA PUBLICA PORTAL ---
// Usuário deslogado consegue ver essas páginas
Route::get('/', Home::class) ->name('home');
Route::get('/home', Home::class) ->name('home');
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');//rota padrao do laravel
Route::get('/verify-email', VerificarEmail::class)->middleware('auth')->name('verification.notice');
Route::get('/consulta/solicitacoes/{autenticidade}', SolicitacaoShow::class)->name('solicitacoes.show.public');//exibição da solcitacao em abas readonly true, rota do qrcode de solicitacoes
Route::get('/consulta/protocolo', SolicitacaoConsulta::class) ->name('protocolo.consulta');//consulta do protocolo
Route::get('/consulta/empresas/regularizadas', EmpresasRegularizadas::class) ->name('empresas.regularizadas');//consulta de empresas regularizadas
Route::get('/consulta/downloads', DownloadsConsulta::class) ->name('downloads.consultas');//consulta de arquivos para downloads
Route::get('/consulta/credenciamentos', EmpresasCredenciadasConsulta::class) ->name('credenciamentos.consultas');//consulta de credenciamentos
Route::get('/consulta/autenticidades', AutenticidadesConsulta::class) ->name('autenticidades.consultas');//consulta de autenticidade
Route::get('/consulta/autenticidades/certificacoes/{codigo}', AutenticidadesConsultaCertificacoes::class) ->name('autenticidades.certificacoes');//consulta de autenticidade de certificacao, rota tambem qrcode
Route::get('/consulta/autenticidades/relatorios/{codigo}', AutenticidadesConsultaRelatorios::class) ->name('autenticidades.relatorios');//consulta de autenticidade de relatorios, rota tambem qrcode
Route::get('/consulta/autenticidades/declaracoes/{codigo}', AutenticidadesConsultaDeclaracoes::class) ->name('autenticidades.declaracoes');//consulta de autenticidade de declaracoes, rota tambem qrcode