<?php

namespace Database\Seeders;

use App\Models\ConnectedObject;
use App\Models\House;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConnectedObjectSeeder extends Seeder
{
    public function run()
    {
        $house = House::first();

        if (!$house) {
            $house = House::create([
                'name' => 'Connect\'Toit',
                'address' => '123 Rue de la Technologie, 75000 Paris',
                'description' => 'La maison connectée par défaut du système'
            ]);
        }

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

        $objects = [
            [
                'name' => 'Thermostat Intelligent',
                'description' => 'Thermostat connecté pour réguler la température de la maison',
                'category' => 'Climatisation',
                'room' => 'Salon',
                'brand' => 'Nest',
                'type' => 'Thermostat',
                'status' => 'Actif',
                'connectivity' => 'Wi-Fi',
                'current_temp' => '21°C',
                'target_temp' => '20°C',
                'is_automated' => true,
                'image' => 'connected_objects/thermostat.jpg'
            ],
            [
                'name' => 'Caméra de Sécurité',
                'description' => 'Caméra de surveillance connectée',
                'category' => 'Sécurité',
                'room' => 'Entrée',
                'brand' => 'Ring',
                'type' => 'Caméra',
                'status' => 'Actif',
                'connectivity' => 'Wi-Fi',
                'is_automated' => true,
                'image' => 'connected_objects/camera.png'
            ],
            [
                'name' => 'Éclairage Intelligent',
                'description' => 'Système d\'éclairage connecté',
                'category' => 'Éclairage',
                'room' => 'Salon',
                'brand' => 'Philips Hue',
                'type' => 'Ampoule',
                'status' => 'Actif',
                'connectivity' => 'Zigbee',
                'is_automated' => true,
                'image' => 'connected_objects/ampoule-intelligente.jpg'
            ],
            [
                'name' => 'Prise Connectée',
                'description' => 'Prise électrique intelligente',
                'category' => 'Énergie',
                'room' => 'Cuisine',
                'brand' => 'TP-Link',
                'type' => 'Prise',
                'status' => 'Actif',
                'connectivity' => 'Wi-Fi',
                'is_automated' => true,
                'image' => 'connected_objects/prise-intelligente.jpg'
            ],
            [
                'name' => 'Détecteur de Fumée',
                'description' => 'Détecteur de fumée connecté',
                'category' => 'Sécurité',
                'room' => 'Couloir',
                'brand' => 'Nest',
                'type' => 'Détecteur',
                'status' => 'Actif',
                'connectivity' => 'Wi-Fi',
                'is_automated' => true,
                'image' => 'connected_objects/detecteur.jpg'
            ]
        ];

        foreach ($objects as $object) {
            ConnectedObject::create(array_merge($object, [
                'house_id' => $house->id,
                'user_id' => $admin->id
            ]));
        }
    }
}
