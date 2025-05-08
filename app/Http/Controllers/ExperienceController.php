<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ExperienceController extends Controller
{
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