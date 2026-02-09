<?php

namespace App\Models;

use App\Notifications\ResetSenhaNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail;

    /**
     * Tabela com schema (PostgreSQL)
     */
    protected $table = 'portal.users';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Auto incremento
     */
    public $incrementing = true;

    /**
     * Tipo da PK
     */
    protected $keyType = 'int';

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'telefone',
        'role',
    ];

    /**
     * Campos ocultos nas serializações
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos
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
