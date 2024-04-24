<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
class LogoutUser extends Controller
{
    public function logout(): RedirectResponse
    {
        if (Auth::check()) {
            Auth::logout();

            Alert::success('Logout efetuado', 'Logout efetuado com sucesso.')->showConfirmButton('OK');
            return redirect()->route('login.cadastro')->with('success', 'Logout efetuado com sucesso.');
        }

        Alert::error('Erro no logout', 'Não foi possível fazer logout. Usuário não autenticado.')->showConfirmButton('OK');
        return redirect()->route('login.cadastro')->with('error', 'Não foi possível fazer logout. Usuário não autenticado.');
    }
}
