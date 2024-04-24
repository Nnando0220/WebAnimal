<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Mail\ResetPasswordSuccessMail;
use App\Models\PasswordResetToken;
use App\Models\User;
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

class RedefinePassword extends Controller
{
    public function verificationEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ]);

        $validatedData = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        $modelToken = new PasswordResetToken();
        $modelToken->saveToken($validatedData['email'], $token);

        $this->sendEmailResetPassword($validatedData['email'], $token);

        Alert::success('Sucesso!', 'Um e-mail foi enviado para redefinir sua senha. Por favor, verifique sua caixa de entrada ou Spam. Ele possui validade de 24 horas!')->showConfirmButton('OK');
        return redirect()->to(route('verificacao.email'))->with('success', 'Um e-mail foi enviado para redefinir sua senha. Por favor, verifique sua caixa de entrada ou Spam. Ele possui validade de 24 horas!');
    }

    private function sendEmailResetPassword($email, $token): void
    {
        Mail::to($email)->queue(new ResetPasswordMail($token));
    }

    private function sendEmailSuccessResetPassword($email): void
    {
        Mail::to($email)->queue(new ResetPasswordSuccessMail());
    }

    public function pageResetPassword($token): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('redefinir_senha', compact('token'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:users',
                'nova_senha' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?!.*[\s])(?!.*[\\/:*?"<>|])[a-zA-Z\d@#$%^&+=!]{8,}$/', 'confirmed'],
                'nova_senha_confirmation' => 'required',
            ]);

            $modelToken = new PasswordResetToken();

            if (!$modelToken->findValidEmail($validatedData['email'], $request->token)){
                Alert::error('Erro!', 'Dados para redefinição de senha não correspondem. Por favor, verifique se estão corretos!')->showConfirmButton('OK');
                return redirect()
                    ->to(route('pagina.redefinir.senha', ['token' => $request->token]))
                    ->with('error', 'Token inválido ou expirado.')
                    ->withInput();
            }

            if (!$modelToken->findValidToken($validatedData['email'], $request->token)) {
                Alert::error('Erro!', 'Token inválido ou expirado.')->showConfirmButton('OK');
                return redirect()
                    ->route('pagina.redefinir.senha')
                    ->with('error', 'Token inválido ou expirado.')
                    ->withInput();
            }

            User::updateUserPassword($validatedData['email'], $validatedData['nova_senha']);

            $modelToken->deleteResetPasswordToken($validatedData['email']);

            $this->sendEmailSuccessResetPassword($validatedData['email']);

            Auth::logout();

            Alert::success('Sucesso!', 'Senha atualizada com sucesso. Faça login novamente.')->showConfirmButton('OK');
            return redirect()->to(route('login.cadastro'))->with('success', 'Senha atualizada com sucesso. Faça login novamente.');

        } catch (ValidationException $exception){
            Alert::error('Erro!', 'Senha não alterada! Tente novamente.')->showConfirmButton('OK');
            return redirect()
                ->route('pagina.redefinir.senha', ['token' => $request->token])
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        }
    }
}
