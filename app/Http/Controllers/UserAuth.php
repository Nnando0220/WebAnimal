<?php

namespace App\Http\Controllers;

use App\Mail\SuccessRegisterEmail;
use App\Mail\VerifyEmailRegisterMail;
use App\Models\User;
use App\Models\VerificationEmail;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class UserAuth extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $user = User::findUser($credentials['email']);

        if (Auth::attempt($credentials, $remember) && User::verifyEmail($user)) {
            Alert::success('Sucesso!', 'Login realizado com sucesso!')->showConfirmButton('OK');
            return redirect()->route('postagens')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas'])->withInput($request->only('email'));
    }

    public function verifyRegisterEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email_cadastro' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ]);

        $validatedData = $request->validate([
            'email_cadastro' => 'required|email|unique:users,email',
        ]);

        $token = Str::random(64);

        $verificationEmail = new VerificationEmail();
        $verificationEmail->saveToken($validatedData['email_cadastro'], $token);

        $this->sendVerificationEmailRegister($validatedData['email_cadastro'], $token);

        Alert::success('Sucesso!', 'Um e-mail para cadastro foi enviado. Por favor, verifique sua caixa de entrada ou Spam. Ele possui validade de 24 horas!')->showConfirmButton('OK');
        return redirect()->to(route('login.cadastro', ['form' => 'cadastro']))->with('success', 'Um e-mail para cadastro foi enviado. Por favor, verifique sua caixa de entrada ou Spam. Ele possui validade de 24 horas!');
    }

    private function sendVerificationEmailRegister($email, $token): void
    {
        Mail::to($email)->queue(new VerifyEmailRegisterMail($token));
    }

    private function sendSuccessRegister($email): void
    {
        Mail::to($email)->queue(new SuccessRegisterEmail());
    }

    public function pageRegisterUser($token): View
    {
        $email = VerificationEmail::findEmailToken($token);
        return view('cadastro', compact('token', 'email'));
    }

    public function registerUser(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'email_cadastro' => 'required|email|unique:users,email',
                'nome_usuario' => 'required|min:6|regex:/^[a-zA-Z\d]{6,}$/|unique:users,username',
                'nome_completo' => ['required', 'regex:/^[a-zA-Z\s]*$/', 'max:255'],
                'senha_cadastro' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?!.*[\s])(?!.*[\\/:*?"<>|])[a-zA-Z\d@#$%^&+=!]{8,}$/'],
                'imagem_perfil' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $verificationModel = new VerificationEmail();

            if ($verificationModel->findValidEmail($validatedData['email_cadastro'], $request->token)) {
                $resetToken = $verificationModel->findValidToken($validatedData['email_cadastro'], $request->token);
                if (!$resetToken) {
                    Alert::error('Erro!', 'Token inválido ou expirado.')->showConfirmButton('OK');
                    return redirect()->to(route('login.cadastro'))->with('error', 'Token inválido ou expirado.');
                }
                if ($request->hasFile('imagem_perfil')) {
                    $image = $request->file('imagem_perfil');

                    if (!$image->isValid()) {
                        Alert::error('Erro!', 'Imagem de perfil não é válida')->showConfirmButton('OK');
                        return redirect()->back()->withErrors(['imagem_perfil' => 'Imagem de perfil não é válida'])->withInput();
                    }

                    $nomeImage = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $contentImage = $this->processImage($image);
                    $successUpload = Storage::disk('azure')->put("photoperfil/$nomeImage", $contentImage);

                    if ($successUpload) {
                        $azureUrl = Storage::disk('azure')->url("photoperfil/$nomeImage");
                        $azureUrl = preg_replace('#([^:])//+#', '$1/', $azureUrl);
                        $user = $this->insertDataUserRegister($validatedData, $azureUrl);
                    }
                } else {
                    $imageNull = "https://cs210032002e0478393.blob.core.windows.net/photoperfil/image_perfil_null.png";
                    $user = $this->insertDataUserRegister($validatedData, $imageNull);
                }
            }

            if ($user) {
                User::changeTimeVerifyEmail($validatedData['email_cadastro']);
                $verificationModel->deleteResetMethodToken($validatedData['email_cadastro']);
                $this->sendSuccessRegister($validatedData['email_cadastro']);

                Alert::success('Sucesso!', 'Seu cadastro foi realizado. Agora faça o login para estar autenticado.')->showConfirmButton('OK');
                return redirect()->route('login.cadastro')->with('success', 'Seu cadastro foi realizado. Agora faça o login para estar autenticado.');
            }

            Alert::error('Erro!', 'Erro ao cadastrar. Tente novamente.')->showConfirmButton('OK');
            return redirect()->to(route('cadastrar.usuario'), [$request->token])
                ->with('error', 'Erro ao cadastrar. Tente novamente.')
                ->withInput();
        } catch (Exception $exception) {
            Alert::error('Erro!', 'Erro ao cadastrar. Tente novamente.')->showConfirmButton('OK');
            return redirect()->to(route('cadastrar.usuario', ['token' => $request->token]))
                ->withErrors($exception->validator->errors()->all())
                ->withInput();
        }
    }

    private function processImage($image): \Intervention\Image\Image
    {
        return Image::make($image)->resize(100, 100)->encode();
    }

    private function insertDataUserRegister($array, $imagePath): User
    {
        return User::createUser([
            'name' => $array['nome_completo'],
            'username' => $array['nome_usuario'],
            'email' => $array['email_cadastro'],
            'password' => $array['senha_cadastro'],
            'photo_url' => $imagePath,
        ]);
    }

    public function verifyUseUsername(Request $request): JsonResponse
    {
        $disponibility = User::findUsedUsername($request->input('username'));
        return response()->json(['disponibilidade' => $disponibility]);
    }
}


