<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HouseSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si une maison existe déjà
        if (House::count() === 0) {
            $admin = User::where('email', 'admin@maisonconnectee.com')->first();

            if (!$admin) {
                $admin = User::create([
                    'name' => 'Administrateur',
                    'email' => 'admin@maisonconnectee.com',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'surname' => 'Admin',
                    'username' => 'admin',
                    'gender' => 'other',
                    'birthdate' => '1990-01-01',
                    'member_type' => 'parent',
                    'level' => 'expert',
                    'points' => 1000
                ]);
            }

            $house = House::create([
                'name' => 'Maison Connectée',
                'address' => '123 Rue de la Technologie',
                'description' => 'Une maison moderne et connectée'
            ]);
        }
    }
} 