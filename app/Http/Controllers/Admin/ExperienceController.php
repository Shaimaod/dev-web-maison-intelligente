<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Contrôleur administratif de gestion du système d'expérience
 * 
 * Ce contrôleur permet aux administrateurs de configurer
 * et de gérer le système d'expérience des utilisateurs
 * directement depuis le panneau d'administration.
 */
class ExperienceController extends Controller
{
    /**
     * Affiche la page de configuration du système d'expérience
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('points')->get();
        $config = Config::get('experience');
        
        return view('admin.experience', compact('users', 'config'));
    }

    /**
     * Met à jour les points et les niveaux d'expérience
     * Modifie directement le fichier .env
     * 
     * @param Request $request La requête contenant les nouveaux paramètres
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePoints(Request $request)
    {
        $request->validate([
            'points' => 'required|array',
            'points.*' => 'required|integer|min:0',
            'levels' => 'required|array',
            'levels.*' => 'required|integer|min:0',
        ]);

        // Mettre à jour les points dans le fichier .env
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($request->points as $key => $value) {
            $envKey = 'EXPERIENCE_POINTS_' . strtoupper($key);
            $envContent = preg_replace(
                "/^{$envKey}=.*/m",
                "{$envKey}={$value}",
                $envContent
            );
        }

        foreach ($request->levels as $key => $value) {
            $envKey = 'EXPERIENCE_LEVEL_' . strtoupper($key);
            $envContent = preg_replace(
                "/^{$envKey}=.*/m",
                "{$envKey}={$value}",
                $envContent
            );
        }

        file_put_contents($envFile, $envContent);

        return redirect()->back()->with('success', 'Configuration mise à jour avec succès');
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
        $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $user->points()->updateOrCreate(
            ['user_id' => $user->id],
            ['current_points' => $request->points]
        );

        return redirect()->back()->with('success', 'Points utilisateur mis à jour avec succès');
    }
}