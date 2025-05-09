<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExperienceService
{
    /**
     * Attribution des points à un utilisateur pour une action
     *
     * @param User $user L'utilisateur qui reçoit les points
     * @param string $action Le type d'action (object_added, profile_update, login, etc.)
     * @return int Nombre de points attribués
     */
    public function addPointsForAction(User $user, string $action): int
    {
        // Convertir action en format de config
        $configKey = $this->getConfigKeyForAction($action);
        
        // Récupérer le nombre de points pour cette action
        // On évite le cache de config pour être sûr d'avoir les dernières valeurs
        $envKey = 'EXPERIENCE_POINTS_' . strtoupper($action);
        $points = (int)env($envKey, 0);
        
        // Log pour debugging
        Log::debug("Attribution de points pour l'action '{$action}', points configurés: {$points}");
        
        if ($points > 0) {
            Log::info("Attribution de {$points} points à l'utilisateur #{$user->id} pour l'action {$action}");
            
            // Ajouter les points à l'utilisateur
            $user->points += $points;
            $user->updateLevel(); // Mettre à jour le niveau si nécessaire
            $user->save();
            
            return $points;
        }
        
        return 0;
    }
    
    /**
     * Convertit un type d'action en clé de configuration
     */
    private function getConfigKeyForAction(string $action): string
    {
        // Mapping des actions vers les clés de config
        $mapping = [
            'login' => 'login',
            'profile_update' => 'profile_update',
            'object_added' => 'object_added',
            'object_update' => 'object_update',
            'object_search' => 'object_search',
            'profile_search' => 'profile_search',
            'status_change' => 'status_change'
        ];
        
        return $mapping[$action] ?? $action;
    }
}
