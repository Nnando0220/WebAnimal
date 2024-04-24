<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class VerifierToken
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $expiredToken = DB::table('emailverification')
            ->where('token', $request->token)
            ->where('expired_at', '>=', now())
            ->first();

        if (!$expiredToken) {
            Alert::error('Erro!', 'Token expirado ou inválido.')->showConfirmButton('OK');
            if (Auth::check()){
                return redirect()->to(route('exibir.redefinicao.email'))->with('error', 'Token expirado ou inválido.');
            }
            return redirect()->to(route('login.cadastro'))->with('error', 'Token expirado ou inválido.');
        }

        return $next($request);
    }
}
