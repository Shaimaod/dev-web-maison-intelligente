<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->getLevel() === 'expert')) {
            return $next($request);
        }
        
        return redirect()->route('home')->with('error', 'Accès non autorisé. Vous devez être administrateur ou de niveau expert pour accéder à cette page.');
    }
}
