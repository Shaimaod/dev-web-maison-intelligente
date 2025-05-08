<?php

/**
 * Routes API - Point d'entrée pour les requêtes API de l'application
 *
 * Ce fichier définit les routes accessibles via les requêtes API :
 * - Routes publiques pour récupérer les informations des objets connectés
 * - Routes protégées pour manipuler les objets connectés via l'API
 * 
 * Ces routes sont préfixées par '/api' et peuvent utiliser
 * une authentification via token ou session web selon les besoins.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectedObjectController;

// Route publique pour récupérer les objets (accessible sans authentification)
Route::get('/objects', [ConnectedObjectController::class, 'getObjects']);

// Groupe des routes API protégées
Route::middleware('web')->group(function () {
    Route::post('/objects', [ConnectedObjectController::class, 'store']);
});
