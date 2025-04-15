<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ExperienceService;

class TrackUserActions
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
        $response = $next($request);
        
        // Vérifier si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();
            $experienceService = app(ExperienceService::class);
            
            // Déterminer le type d'action en fonction de la route
            $route = $request->route()->getName();
            
            // Si c'est une recherche d'objet
            if (strpos($route, 'getObjects') !== false || strpos($route, 'search') !== false) {
                $experienceService->addPointsForAction($user, 'object_search');
            }
            
            // Si c'est une mise à jour d'objet
            if ($request->isMethod('put') || $request->isMethod('patch')) {
                if (strpos($route, 'object') !== false && strpos($route, 'update') !== false) {
                    $experienceService->addPointsForAction($user, 'object_update');
                }
                
                // Si c'est une mise à jour de statut
                if ($request->has('status')) {
                    $experienceService->addPointsForAction($user, 'status_change');
                }
            }
        }
        
        return $response;
    }
}
