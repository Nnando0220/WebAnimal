<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerifyEmailReset extends Mailable
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build(): VerifyEmailReset
    {
        return $this->view('emails.verificacao_email_redefinicao')
            ->subject('Verificar E-mail');
    }
}
