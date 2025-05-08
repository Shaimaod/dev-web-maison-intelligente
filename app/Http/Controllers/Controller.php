<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Contrôleur de base dont tous les autres contrôleurs héritent
 * 
 * Ce contrôleur fournit des fonctionnalités communes à tous les contrôleurs
 * de l'application en intégrant les traits pour gérer l'autorisation et
 * la validation des requêtes.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
