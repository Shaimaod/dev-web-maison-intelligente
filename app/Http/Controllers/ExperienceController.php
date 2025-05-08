<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

        $levels = [
            'beginner' => env('EXPERIENCE_LEVEL_BEGINNER', 0),
            'intermediate' => env('EXPERIENCE_LEVEL_INTERMEDIATE', 50),
            'advanced' => env('EXPERIENCE_LEVEL_ADVANCED', 100),
            'expert' => env('EXPERIENCE_LEVEL_EXPERT', 200),
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
            'beginner' => 'required|integer|min:0',
            'intermediate' => 'required|integer|min:0',
            'advanced' => 'required|integer|min:0',
            'expert' => 'required|integer|min:0',
        ]);

        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        foreach ($validated as $key => $value) {
            $pattern = "/EXPERIENCE_(POINTS|LEVEL)_" . strtoupper($key) . "=.*/";
            $replacement = "EXPERIENCE_${1}_" . strtoupper($key) . "=" . $value;
            $envContent = preg_replace($pattern, $replacement, $envContent);
        }

        File::put($envFile, $envContent);

        return redirect()->route('admin.experience')
            ->with('success', 'Les points d\'expérience ont été mis à jour avec succès.');
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
        $user->save();

        return redirect()->route('admin.experience')
            ->with('success', 'Les points de l\'utilisateur ont été mis à jour avec succès.');
    }
}