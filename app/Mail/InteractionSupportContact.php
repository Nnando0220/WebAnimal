<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InteractionSupportContact extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build(): InteractionSupportContact
    {
        return $this->view('emails.interacao_suporte_contato')
            ->with(['data' => $this->data])
            ->subject('Contato do usuario para '.$this->data['opcoes']);
    }
}
