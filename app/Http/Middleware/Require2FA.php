<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Require2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->google2fa_login_enabled && !session('2fa_verified')) {
            return redirect()->route('2fa.verify.login');
        }

        return $next($request);
    }
}
