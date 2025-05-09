<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

/**
 * Contrôleur de gestion du système d'expérience utilisateur
 * 
 * Ce contrôleur permet de configurer et gérer le système d'expérience
 * qui permet aux utilisateurs de progresser en niveaux en fonction
 * de leurs interactions avec l'application.
 */
class ExperienceController extends Controller
{
    /**
     * Affiche la page de configuration du système d'expérience
     * Accessible uniquement aux administrateurs
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        $points = [
            'object_added' => env('EXPERIENCE_POINTS_OBJECT_ADDED', 10),
            'profile_update' => env('EXPERIENCE_POINTS_PROFILE_UPDATE', 5),
            'login' => env('EXPERIENCE_POINTS_LOGIN', 1),
            'object_search' => env('EXPERIENCE_POINTS_OBJECT_SEARCH', 1),
            'profile_search' => env('EXPERIENCE_POINTS_PROFILE_SEARCH', 1),
            'object_update' => env('EXPERIENCE_POINTS_OBJECT_UPDATE', 3),
        ];

        // Get level thresholds from configuration rather than directly from env
        $levelsConfig = Config::get('levels.levels');
        $levels = [
            'beginner' => 0, // Beginner always starts at 0
            'intermediate' => $levelsConfig['intermédiaire']['min_points'] ?? env('EXPERIENCE_LEVEL_INTERMEDIATE', 50),
            'advanced' => $levelsConfig['avancé']['min_points'] ?? env('EXPERIENCE_LEVEL_ADVANCED', 100),
            'expert' => $levelsConfig['expert']['min_points'] ?? env('EXPERIENCE_LEVEL_EXPERT', 200),
        ];

        return view('admin.experience.index', compact('users', 'points', 'levels'));
    }

    /**
     * Met à jour les points attribués pour différentes actions
     * et les seuils des niveaux d'expérience
     * 
     * @param Request $request La requête contenant les nouvelles valeurs
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePoints(Request $request)
    {
        $validated = $request->validate([
            'object_added' => 'required|integer|min:0',
            'profile_update' => 'required|integer|min:0',
            'login' => 'required|integer|min:0',
            'object_search' => 'required|integer|min:0',
            'profile_search' => 'required|integer|min:0',
            'object_update' => 'required|integer|min:0',
            'beginner' => 'nullable|integer|min:0',
            'intermediate' => 'nullable|integer|min:0',
            'advanced' => 'nullable|integer|min:0',
            'expert' => 'nullable|integer|min:0',
        ]);

        // Mettre à jour les variables d'environnement pour les points
        $this->updateEnvFile('EXPERIENCE_POINTS_OBJECT_ADDED', $validated['object_added']);
        $this->updateEnvFile('EXPERIENCE_POINTS_PROFILE_UPDATE', $validated['profile_update']);
        $this->updateEnvFile('EXPERIENCE_POINTS_LOGIN', $validated['login']);
        $this->updateEnvFile('EXPERIENCE_POINTS_OBJECT_SEARCH', $validated['object_search']);
        $this->updateEnvFile('EXPERIENCE_POINTS_PROFILE_SEARCH', $validated['profile_search']);
        $this->updateEnvFile('EXPERIENCE_POINTS_OBJECT_UPDATE', $validated['object_update']);

        // Mettre à jour les variables d'environnement pour les niveaux
        if (isset($validated['beginner'])) {
            $this->updateEnvFile('EXPERIENCE_LEVEL_BEGINNER', $validated['beginner']);
        }
        if (isset($validated['intermediate'])) {
            $this->updateEnvFile('EXPERIENCE_LEVEL_INTERMEDIATE', $validated['intermediate']);
        }
        if (isset($validated['advanced'])) {
            $this->updateEnvFile('EXPERIENCE_LEVEL_ADVANCED', $validated['advanced']);
        }
        if (isset($validated['expert'])) {
            $this->updateEnvFile('EXPERIENCE_LEVEL_EXPERT', $validated['expert']);
        }

        // Vider le cache de configuration
        \Artisan::call('config:clear');
        
        // Mettre à jour tous les niveaux des utilisateurs après changement des paliers
        $this->updateAllUserLevels();

        return redirect()->route('admin.experience')
            ->with('success', 'Les points d\'expérience ont été mis à jour avec succès et les niveaux utilisateurs recalculés.');
    }

    /**
     * Met à jour une variable d'environnement dans le fichier .env
     * 
     * @param string $key La clé de la variable
     * @param mixed $value La nouvelle valeur
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            // Remplacer la valeur si la clé existe
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace('/'. $key .'=(.*)/', $key . '=' . $value, $content);
            } else {
                // Ajouter la clé si elle n'existe pas
                $content .= "\n" . $key . '=' . $value;
            }

            file_put_contents($path, $content);
        }
    }

    /**
     * Met à jour manuellement les points d'un utilisateur spécifique
     * 
     * @param Request $request La requête contenant les nouveaux points
     * @param User $user L'utilisateur à modifier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserPoints(Request $request, User $user)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $user->points = $validated['points'];
        
        // Mettre à jour le niveau de l'utilisateur en fonction des points
        if (method_exists($user, 'updateLevel')) {
            $user->updateLevel();
        }
        
        $user->save();

        return redirect()->route('admin.experience')
            ->with('success', 'Les points et le niveau de l\'utilisateur ont été mis à jour avec succès.');
    }
    
    /**
     * Met à jour tous les niveaux utilisateurs en fonction des nouveaux paliers
     */
    private function updateAllUserLevels()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (method_exists($user, 'updateLevel')) {
                $user->updateLevel();
                $user->save();
            }
        }
    }
}