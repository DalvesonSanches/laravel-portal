<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Portal\Home;

use App\Livewire\Guest\Login;
use App\Livewire\Guest\Registro;
use App\Livewire\Guest\RecuperarSenha;
use App\Livewire\Guest\RedefinirSenha;
use App\Livewire\Guest\ConfirmarSenha;
use App\Livewire\Guest\VerificarEmail;

use App\Livewire\Auth\Solicitacoes\MeusProtocolos;
use App\Livewire\Auth\Solicitacoes\SolicitacoesCreate;
use App\Livewire\Auth\Dashboard;
use App\Livewire\Auth\Profile\Profile;
use App\Livewire\Auth\Users\UserIndex;
use App\Livewire\Auth\Users\UserEdit;
use App\Livewire\Auth\Users\UserShow;
use App\Http\Controllers\Auth\VerifyEmailController;
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
    Route::get('/meus-protocolos', MeusProtocolos::class)->name('meus-protocolos');//blade de meus protocolos
    Route::get('/solicitacoes', SolicitacoesCreate::class)->name('solicitacoes.create');//formulario de criação da solicitacao
    Route::get('/dashboard', Dashboard::class)->name('dashboard');//dashboard apos login
    Route::get('/profile', Profile::class)->name('profile');//perfil do usuario
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