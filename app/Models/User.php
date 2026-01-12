<?php

namespace App\Models;

use App\Notifications\ResetSenhaNotification;
use Illuminate\Auth\MustVerifyEmail; // Trait
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'telefone',
        'rule',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔐 Envio customizado de redefinição de senha
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetSenhaNotification($token));
    }
}