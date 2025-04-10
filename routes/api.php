<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectedObjectController;

// Groupe des routes API
Route::middleware('api')->group(function () {
    Route::get('/objects', [ConnectedObjectController::class, 'getObjects']);
    Route::post('/objects', [ConnectedObjectController::class, 'store'])->middleware('auth:sanctum');
});
