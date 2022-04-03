<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->role === 'admin' && $user->deces === 0 && $user->retraite === 0 && $user->exclut === 0) {
            return $next($request);
        }
        return redirect()->route('dashboard');
    }
}