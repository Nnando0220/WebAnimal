<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function __construct()
    {
        Auth::setDefaultDriver('admin');
        config(['auth.defaults.passwords' => 'admin']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        $guard = Auth::guard('admin');

        if ($guard->attempt($credentials)) {
            Alert::success('Sucesso!', 'Login realizado com sucesso!')->showConfirmButton('OK');
            return redirect()->route('crud.pagina.users')->with('success', 'Login realizado com sucesso!');
        }

        return redirect()->route('crud.pagina.login')->withErrors(['username' => 'Credenciais inválidas'])->withInput($request->only('username'));
    }
}
