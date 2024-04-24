<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build(): VerifyEmailRegisterMail
    {
        return $this->view('emails.verificar_email_cadastro')
            ->subject('Verificar E-mail');
    }
}
