<?php

namespace App\Http\Controllers;

use App\Mail\ResetEmailSuccess;
use App\Mail\VerifyEmailReset;
use App\Models\User;
use App\Models\VerificationEmail;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class RedefineEmail extends Controller
{
    public function verifyEmailReset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ]);

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $token = Str::random(64);

        $emailVerification = new VerificationEmail();
        $emailVerification->saveToken($validatedData['email'], $token);

        $this->sendVerificationEmailReset($validatedData['email'], $token);

        Alert::success('Sucesso!', 'Um e-mail foi enviado para redefinir seu E-mail. Por favor, verifique sua caixa de entrada ou Spam. Ele possui validade de 24 horas!')->showConfirmButton('OK');
        return redirect()->to(route('exibir.redefinicao.email'))->with('success', 'Um e-mail foi enviado para redefinir seu E-mail. Verifique também seu Spam. Ele possui validade de 24 horas!');
    }

    private function sendVerificationEmailReset($email, $token): void
    {
        Mail::to($email)->queue(new VerifyEmailReset($token));
    }

    private function sendSuccessResetEmail($email): void
    {
        Mail::to($email)->queue(new ResetEmailSuccess());
    }

    public function pageResetEmail($token): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('redefinir_email', compact('token'));
    }

    function redefineEmail(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email|confirmed',
                'email_confirmation' => 'required',
            ]);

            $modelVerification = new VerificationEmail();

            if (!$modelVerification->findValidEmail($validatedData['email'], $request->token)){
                Alert::error('Erro!', 'Dado para redefinição de email não corresponde. Por favor, verifique se esta correto!')->showConfirmButton('OK');
                return redirect()
                    ->route('pagina.redefinir.email', ['token' => $request->token])
                    ->with('error', 'Token inválido ou expirado.')
                    ->withInput();
            }

            if (!$modelVerification->findValidToken($validatedData['email'], $request->token)) {
                Alert::error('Erro!', 'Token inválido ou expirado.')->showConfirmButton('OK');
                return redirect()->to(route('exibir.redefinicao.email'))->with('error', 'Token inválido ou expirado.');
            }

            User::modificationEmail(Auth::id(), $validatedData['email']);
            User::changeTimeVerifyEmail($validatedData['email']);

            $modelVerification->deleteResetMethodToken($validatedData['email']);

            $this->sendSuccessResetEmail($validatedData['email']);

            Auth::logout();

            Alert::success('Sucesso!', 'E-mail atualizado com sucesso. Faça login novamente.')->showConfirmButton('OK');
            return redirect()->route('login.cadastro')->with('success', 'E-mail atualizado com sucesso. Faça login novamente.');

        }  catch (ValidationException $exception){
            Alert::error('Erro!', 'E-mail não alterado! Tente novamente.')->showConfirmButton('OK');
            return redirect()
            ->route('pagina.redefinir.email', ['token' => $request->token])
            ->withErrors($exception->validator->errors()->all())
            ->withInput();
        }
    }
}
