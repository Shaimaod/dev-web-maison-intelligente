<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * DatabaseSeeder - Classe pour peupler la base de données avec des données initiales
 * Cette classe est exécutée lors de la commande 'php artisan db:seed'
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Exécute les seeders pour peupler la base de données.
     * Crée les utilisateurs par défaut et exécute les autres seeders.
     */
    public function run(): void
    {
        // Créer l'administrateur (ou le mettre à jour s'il existe déjà)
        // Cet utilisateur aura tous les droits d'administration
        User::updateOrCreate(
            ['email' => 'admin@maisonconnectee.com'], // Identifiant unique pour la recherche
            [
                'name' => 'Administrateur',
                'email_verified_at' => now(), // Email déjà vérifié
                'password' => Hash::make('admin123'), // Mot de passe hashé pour sécurité
                'remember_token' => Str::random(10), // Token pour la fonction "Se souvenir de moi"
                'role' => 'admin', // Rôle administrateur
                'surname' => 'Admin',
                'username' => 'admin',
                'gender' => 'other',
                'birthdate' => '1990-01-01',
                'member_type' => 'parent', // Type de membre: parent
                'level' => 'expert', // Niveau expert (accès à toutes les fonctionnalités)
                'points' => 1000 // Points d'expérience initiaux
            ]
        );

        // Créer un utilisateur test pour les démonstrations et les tests
        // Cet utilisateur a des droits limités (utilisateur standard)
        User::updateOrCreate(
            ['email' => 'test@example.com'], // Identifiant unique pour la recherche
            [
                'name' => 'Test User',
                'email_verified_at' => now(), // Email déjà vérifié
                'password' => Hash::make('password'), // Mot de passe hashé
                'remember_token' => Str::random(10), // Token pour la fonction "Se souvenir de moi"
                'surname' => 'User',
                'username' => 'testuser',
                'gender' => 'other',
                'birthdate' => '1995-01-01',
                'member_type' => 'child', // Type de membre: enfant
                'level' => 'débutant', // Niveau débutant (fonctionnalités limitées)
                'points' => 0 // Aucun point d'expérience initial
            ]
        );

        // Appeler les autres seeders pour compléter la population de la base de données
        $this->call([
            AuthorizedUsersSeeder::class, // Génère les utilisateurs autorisés à s'inscrire
            HouseSeeder::class,           // Génère les données des maisons
            ConnectedObjectSeeder::class, // Génère les objets connectés par défaut
        ]);
    }
}
