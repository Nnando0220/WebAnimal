<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessRegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): SuccessRegisterEmail
    {
        return $this->view('emails.cadastro_realizado')
            ->subject('Cadastro realizado com Sucesso');
    }
}
