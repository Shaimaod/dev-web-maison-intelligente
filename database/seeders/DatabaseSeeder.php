<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'administrateur
        User::updateOrCreate(
            ['email' => 'admin@maisonconnectee.com'],
            [
                'name' => 'Administrateur',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'surname' => 'Admin',
                'username' => 'admin',
                'gender' => 'other',
                'birthdate' => '1990-01-01',
                'member_type' => 'parent',
                'level' => 'expert',
                'points' => 1000
            ]
        );

        // Créer un utilisateur test
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'surname' => 'User',
                'username' => 'testuser',
                'gender' => 'other',
                'birthdate' => '1995-01-01',
                'member_type' => 'child',
                'level' => 'débutant',
                'points' => 0
            ]
        );

        $this->call([
            AuthorizedUsersSeeder::class,
            HouseSeeder::class,
            ConnectedObjectSeeder::class,
        ]);
    }
}
