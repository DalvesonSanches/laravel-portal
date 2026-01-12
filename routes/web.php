<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Registro;
use App\Livewire\RecuperarSenha;
use App\Livewire\RedefinirSenha;
use App\Livewire\ConfirmarSenha;
use App\Livewire\VerificarEmail;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');//login
    Route::get('/registro', Registro::class)->name('registro');//novo usuario
    Route::get('/recuperar-senha', RecuperarSenha::class)->name('password.request');//recuperar senha
    Route::get('/reset-password/{token}', RedefinirSenha::class)->name('password.reset');//redefinir senha
    Route::get('/confirm-password', ConfirmarSenha::class)->name('password.confirm');//confirmar senha
    Route::get('/verify-email', VerificarEmail::class)->name('verification.notice');//verificar email
});

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');//rota padrao do laravel

Route::get('/', Home::class)
    ->name('home');
Route::get('/home', Home::class)
    ->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//require __DIR__.'/auth.php';
