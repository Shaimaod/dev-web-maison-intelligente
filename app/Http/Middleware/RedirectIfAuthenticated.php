<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        // Si l'utilisateur est déjà authentifié, on le redirige vers son tableau de bord
        if (Auth::check()) {
            // On vérifie si l'utilisateur est déjà sur les pages de connexion ou d'inscription
            if ($request->is('login') || $request->is('register')) {
                return redirect()->route('dashboard.connected');  // Redirection vers le dashboard connecté
            }
        }

        // Si l'utilisateur n'est pas authentifié ou s'il n'est pas sur la page de login ou register, continuer le flux
        return $next($request);
    }
}



