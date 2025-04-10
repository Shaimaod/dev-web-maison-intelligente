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
        // Valider les données du formulaire avec des règles plus strictes
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u', // Uniquement lettres, espaces et tirets
            'surname' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/', // Uniquement lettres, chiffres et underscores
                'unique:users,username,' . Auth::id(),
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns', // Validation plus stricte de l'email
                'max:255',
                'unique:users,email,' . Auth::id(),
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif', // Types de fichiers autorisés
                'max:2048', // 2MB max
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000', // Dimensions min/max
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', // Au moins une majuscule, une minuscule, un chiffre et un caractère spécial
            ],
            'role' => 'nullable|in:user,admin', // Validation du rôle
        ], [
            'name.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'surname.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'username.regex' => 'Le nom d\'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'photo.mimes' => 'Le format de l\'image doit être jpeg, png, jpg ou gif.',
            'photo.dimensions' => 'L\'image doit avoir une taille comprise entre 100x100 et 2000x2000 pixels.',
        ]);

        try {
            // Obtenir l'utilisateur connecté
            $user = Auth::user();

            // Mettre à jour les informations de l'utilisateur
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->username = $request->input('username');
            $user->email = $request->input('email');

            // Gérer l'upload de la photo de manière sécurisée
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
                    unlink(public_path('storage/' . $user->photo));
                }
                
                // Générer un nom de fichier unique et sécurisé
                $filename = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                
                // Enregistrer la nouvelle photo
                $photoPath = $request->file('photo')->storeAs('profiles', $filename, 'public');
                $user->photo = $photoPath;
            }

            // Mettre à jour le mot de passe si un nouveau est fourni
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            // Mettre à jour le rôle si l'utilisateur est admin
            if ($request->filled('role') && $user->isAdmin()) {
                $user->role = $request->input('role');
            }

            // Ajouter des points pour la mise à jour du profil
            $user->addPoints('profile_update');

            // Sauvegarder les changements dans la base de données
            $user->save();

            // Rediriger vers la page de profil avec un message de succès
            return redirect()->route('profile.show')->with('status', 'Profil mis à jour avec succès!');
        } catch (\Exception $e) {
            // Log l'erreur
            \Log::error('Erreur lors de la mise à jour du profil: ' . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du profil. Veuillez réessayer.']);
        }
    }

    /**
     * Rechercher des profils d'utilisateurs
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $users = User::when($query, function($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
        })->paginate(10);

        return view('profiles.search', compact('users', 'query'));
    }

    /**
     * Afficher le profil d'un utilisateur spécifique
     */
    public function showProfile(User $user)
    {
        return view('profiles.show', compact('user'));
    }

    // Afficher le formulaire de modification du profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
}




