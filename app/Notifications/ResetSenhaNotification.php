<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetSenhaNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Redefinição de senha - SISTEC')
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir sua senha.')
            ->action('Redefinir senha', $url)
            ->line('Este link expira em 60 minutos.')
            ->line('Se você não solicitou a redefinição, ignore este e-mail.')
            ->salutation('Atenciosamente, CBMAP');
    }
}