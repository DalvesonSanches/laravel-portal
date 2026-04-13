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
    Route::get('/portal/login', Login::class)->name('login');//login
    Route::get('/portal/registro', Registro::class)->name('registro');//novo usuario
    Route::get('/portal/recuperar-senha', RecuperarSenha::class)->name('password.request');//recuperar senha
    Route::get('/portal/reset-password/{token}', RedefinirSenha::class)->name('password.reset');//redefinir senha
    Route::get('/portal/confirm-password', ConfirmarSenha::class)->name('password.confirm');//confirmar senha
});

// --- ÁREA RESTRITA LOGADO (AUTH) ---
// Usuário deslogado NÃO consegue ver essas páginas
Route::middleware('auth')->group(function () {
    
    Route::get('/painel/solicitacao/index', MeusProtocolos::class)->name('meus.protocolos');//blade de meus protocolos
    Route::get('/painel/solicitacao/create', SolicitacoesCreate::class)->name('solicitacoes.create');//formulario de criação da solicitacao
    Route::get('/painel/solicitacao/show/{autenticidade}', SolicitacaoShow::class)->name('solicitacoes.show');//exibição da solicitacao em abas readonly false
    Route::get('/painel/dashboard/index', Dashboard::class)->name('dashboard');//dashboard apos login
    Route::get('/painel/perfil/index', Profile::class)->name('profile');//perfil do usuario
    Route::get('/painel/download/index', DownloadsIndex::class)->name('downloads.index');//blade de downloads de anexos
    Route::get('/paienl/download/create', DownloadsForm::class)->name('downloads.create');//create do dowload
    Route::get('/painel/download/show/{downloads}', DownloadsShow::class)->name('downloads.show');//exibir registro download
    Route::get('/painel/download/edit/{downloads}', DownloadsForm::class)->name('downloads.edit');//edit do download
    
    Route::get('/painel/users/index', UserIndex::class)->name('users.index');//listagem dos usuarios
    Route::get('/painel/users/show/{user}', UserShow::class)->name('users.show');//exibir usuario
    Route::get('/painel/users/edit/{user}', UserEdit::class)->name('users.edit');//edit do usuairo
    Route::post('/painel/logout', [LogoutController::class, 'logout'])->name('logout');//sair do sistema
});

// --- ÁREA PUBLICA PORTAL ---
// Usuário deslogado consegue ver essas páginas
Route::get('/', Home::class) ->name('home');
Route::get('/portal/home/index', Home::class) ->name('home');
Route::get('/portal/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');//rota padrao do laravel
Route::get('/portal/verify-email', VerificarEmail::class)->middleware('auth')->name('verification.notice');
Route::get('/portal/consulta/solicitacoes/show/{autenticidade}', SolicitacaoShow::class)->name('solicitacoes.show.public');//exibição da solcitacao em abas readonly true, rota do qrcode de solicitacoes
Route::get('/portal/consulta/protocolo', SolicitacaoConsulta::class) ->name('protocolo.consulta');//consulta do protocolo
Route::get('/portal/consulta/empresas/regularizadas/index', EmpresasRegularizadas::class) ->name('empresas.regularizadas');//consulta de empresas regularizadas
Route::get('/portal/consulta/downloads/index', DownloadsConsulta::class) ->name('downloads.consultas');//consulta de arquivos para downloads
Route::get('/portal/consulta/credenciamentos/index', EmpresasCredenciadasConsulta::class) ->name('credenciamentos.consultas');//consulta de credenciamentos
Route::get('/portal/consulta/autenticidades/index', AutenticidadesConsulta::class) ->name('autenticidades.consultas');//consulta de autenticidade
Route::get('/portal/consulta/autenticidades/certificacoes/{codigo}', AutenticidadesConsultaCertificacoes::class) ->name('autenticidades.certificacoes');//consulta de autenticidade de certificacao, rota tambem qrcode
Route::get('/portal/consulta/autenticidades/relatorios/{codigo}', AutenticidadesConsultaRelatorios::class) ->name('autenticidades.relatorios');//consulta de autenticidade de relatorios, rota tambem qrcode
Route::get('/portal/consulta/autenticidades/declaracoes/{codigo}', AutenticidadesConsultaDeclaracoes::class) ->name('autenticidades.declaracoes');//consulta de autenticidade de declaracoes, rota tambem qrcode