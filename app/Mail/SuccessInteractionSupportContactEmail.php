<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessInteractionSupportContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build(): SuccessInteractionSupportContactEmail
    {
        return $this->view('emails.sucesso_suporte_contato')
            ->with(['data' => $this->data])
            ->subject('Sua solicitação para '. $this->data['opcoes']);
    }
}
