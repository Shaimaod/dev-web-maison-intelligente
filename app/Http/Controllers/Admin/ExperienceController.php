<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ExperienceController extends Controller
{
    public function index()
    {
        $users = User::with('points')->get();
        $config = Config::get('experience');
        
        return view('admin.experience', compact('users', 'config'));
    }

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