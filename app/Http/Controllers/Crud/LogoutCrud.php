<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LogoutCrud extends Controller
{
    public function logout(): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Alert::success('Logout efetuado', 'Logout efetuado com sucesso.')->showConfirmButton('OK');
            return redirect()->route('crud.pagina.login')->with('success', 'Logout efetuado com sucesso.');
        }

        Alert::error('Erro no logout', 'Não foi possível fazer logout. Usuário não autenticado.')->showConfirmButton('OK');
        return redirect()->route('crud.pagina.users')->with('error', 'Não foi possível fazer logout. Usuário não autenticado.');
    }
}
