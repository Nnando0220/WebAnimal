<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request, string ...$guards): ?string
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if($guard === 'admin'){
                    return redirect()->route('crud.pagina.users');
                }
                return redirect()->route('postagens');
            }
        }

        if (!$request->expectsJson()) {
            if($request->routeIs('crud.*')){
                return route('crud.pagina.login');
            }
            return route('login.cadastro');
        }
        return $request->expectsJson() ? null : route('login.cadastro');
    }
}
