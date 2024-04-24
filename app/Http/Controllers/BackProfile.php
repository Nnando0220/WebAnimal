<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackProfile extends Controller
{
    public function voltarPerfil(): RedirectResponse
    {
        $user = User::findUser(Auth::id());
        $username = $user->username;

        return redirect()
            ->to(route('exibir.perfil', [
                'username' => $username,
            ]));
    }
}
