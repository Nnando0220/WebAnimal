<?php

namespace App\Http\Controllers;

use App\Mail\SuccessInteractionSupportContactEmail;
use App\Mail\InteractionSupportContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class SupportContact extends Controller
{
    public function formSupportContact(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ]);

        $validatedData = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email',
            'mensagem' => 'required|string|max:1000',
            'opcoes' => 'required|in:feedback,suporte',
        ], [
            'opcoes.in' => 'Escolha uma opção válida.',
        ]);

        if (!$validatedData) {
            Alert::error('Erro!', 'Ouve um erro na validação dos dados. Por favor refaça a solicitação.')->showConfirmButton('OK');
            return redirect()->back()->withErrors('Ouve um erro na validação dos dados. Por favor refaça a solicitação.')->withInput();
        }
        $this->sendEmailSupportContact($validatedData);
        $this->sendEmailSuccessInteractionSupportContact($validatedData);

        Alert::success('Sucesso!', 'Sua solicitação para contato foi enviada. Verifique sua caixa de E-mail.')->showConfirmButton('OK');
        return redirect()->back()->with('success', 'Sua solicitação para contato foi enviada. Verifique sua caixa de E-mail.');
    }

    private function sendEmailSupportContact($array): void
    {
        $adminEmail = Config::get('mail.from.address');
        Mail::to($adminEmail)->queue(new InteractionSupportContact($array));
    }

    private function sendEmailSuccessInteractionSupportContact($data): void
    {
        Mail::to($data['email'])->queue(new SuccessInteractionSupportContactEmail($data));
    }
}
