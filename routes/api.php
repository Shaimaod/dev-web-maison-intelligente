<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectedObjectController;

// Route publique pour récupérer les objets (accessible sans authentification)
Route::get('/objects', [ConnectedObjectController::class, 'getObjects']);

// Groupe des routes API protégées
Route::middleware('web')->group(function () {
    Route::post('/objects', [ConnectedObjectController::class, 'store']);
});
