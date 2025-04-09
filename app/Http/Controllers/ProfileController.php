<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Afficher le profil de l'utilisateur
    public function show()
    {
        // Obtenir les informations de l'utilisateur connecté
        $user = Auth::user();
        
        // Déterminer le niveau de l'utilisateur basé sur ses points
        if ($user->points >= 0 && $user->points < 10) {
            $user->level = 'débutant';
        } elseif ($user->points >= 10 && $user->points < 20) {
            $user->level = 'intermédiaire';
        } elseif ($user->points >= 20 && $user->points < 30) {
            $user->level = 'avancé';
        } else {
            $user->level = 'expert';
        }

        // Sauvegarder le niveau dans la base de données
        $user->save();

        // Retourner la vue 'profile.show' et envoyer l'utilisateur en paramètre
        return view('profile.show', compact('user'));
    }

    // Mettre à jour le profil de l'utilisateur
    public function update(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'photo' => 'nullable|image|max:2048', // Si une photo est uploadée
            'password' => 'nullable|string|min:8|confirmed', // Validation pour le mot de passe
        ]);

        // Obtenir l'utilisateur connecté
        $user = Auth::user();

        // Mettre à jour les informations de l'utilisateur
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo) {
                unlink(public_path('storage/' . $user->photo));
            }
            // Enregistrer la nouvelle photo
            $photoPath = $request->file('photo')->store('profiles', 'public');
            $user->photo = $photoPath;
        }

        // Mettre à jour le mot de passe si un nouveau est fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Déterminer le niveau de l'utilisateur basé sur ses points
        if ($user->points >= 0 && $user->points < 10) {
            $user->level = 'débutant';
        } elseif ($user->points >= 10 && $user->points < 20) {
            $user->level = 'intermédiaire';
        } elseif ($user->points >= 20 && $user->points < 30) {
            $user->level = 'avancé';
        } else {
            $user->level = 'expert';
            $user->role = 'admin';  // Changer le rôle en admin
        }

        // Sauvegarder les changements dans la base de données
        $user->save();

        // Rediriger vers la page de profil avec un message de succès
        return redirect()->route('profile.show')->with('status', 'Profil mis à jour avec succès!');
    }
}




