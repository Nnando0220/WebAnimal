<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetEmailSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): ResetEmailSuccess
    {
        return $this->view('emails.email_alterado')
            ->subject('E-mail Alterado com Sucesso');
    }
}
