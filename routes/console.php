<?php

/**
 * Routes Console - Commandes personnalisées pour l'interface en ligne de commande
 * 
 * Ce fichier définit les routes pour les commandes console personnalisées
 * qui peuvent être exécutées via l'interface en ligne de commande Artisan.
 * Ces commandes peuvent être utilisées pour des tâches d'administration,
 * de maintenance ou des scripts récurrents.
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Commande pour afficher une citation inspirante
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
