<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): ResetPasswordSuccessMail
    {
        return $this->view('emails.senha_alterada')
            ->subject('Senha Alterada com Sucesso');
    }
}
