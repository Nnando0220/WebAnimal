<?php

namespace App\Http\Middleware;

use App\Models\PasswordResetToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class VerifierRedefinitionsPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $expiredToken = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('expired_at', '>=', now())
            ->first();

        if (!$expiredToken){
            Alert::error('Erro!', 'Token expirado ou inválido.')->showConfirmButton('OK');
            if (Auth::check()){
                return redirect()->to(route('verificacao.email'))->with('error', 'Token expirado ou inválido.');
            }
            return redirect()->to(route('login.cadastro'))->with('error', 'Token expirado ou inválido.');
        }

        return $next($request);
    }
}
