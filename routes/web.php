<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Registro;
use App\Livewire\RecuperarSenha;
use App\Livewire\RedefinirSenha;
use App\Livewire\ConfirmarSenha;
use App\Livewire\VerificarEmail;

use App\Livewire\Auth\Users\UserIndex;
use App\Livewire\Auth\Users\UserCreate;
use App\Livewire\Auth\Users\UserEdit;
use App\Livewire\Auth\Users\UserShow;

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Auth\LogoutController;
use Illuminate\Support\Facades\Auth;

// --- ÁREA PÚBLICA (GUEST) ---
// Usuário logado NÃO consegue ver essas páginas
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');//login
    Route::get('/registro', Registro::class)->name('registro');//novo usuario
    Route::get('/recuperar-senha', RecuperarSenha::class)->name('password.request');//recuperar senha
    Route::get('/reset-password/{token}', RedefinirSenha::class)->name('password.reset');//redefinir senha
    Route::get('/confirm-password', ConfirmarSenha::class)->name('password.confirm');//confirmar senha
    //Route::get('/verify-email', VerificarEmail::class)->name('verification.notice');//verificar email
});

// --- ÁREA RESTRITA (AUTH) ---
// Usuário deslogado NÃO consegue ver essas páginas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('livewire.auth.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('livewire.auth.profile.profile');
    })->name('profile');

    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/{user}', UserShow::class)->name('users.show');
    Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

    // O Logout PRECISA do auth, pois só quem está dentro pode sair
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// --- ÁREA PUBLICA ---
// Usuário deslogado consegue ver essas páginas
Route::get('/', Home::class) ->name('home');
Route::get('/home', Home::class) ->name('home');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');//rota padrao do laravel
Route::get('/verify-email', VerificarEmail::class)->middleware('auth')->name('verification.notice');