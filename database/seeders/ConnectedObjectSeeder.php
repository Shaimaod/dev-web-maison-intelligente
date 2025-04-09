<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConnectedObject;

class ConnectedObjectSeeder extends Seeder
{
    public function run()
    {
        ConnectedObject::create([
            'name' => 'Lampe Connectée',
            'description' => 'Une lampe connectée que vous pouvez contrôler via votre téléphone.',
            'category' => 'Éclairage',
            'brand' => 'Philips',
            'type' => 'Lampe',
            'status' => 'Actif',
            'connectivity' => 'Wi-Fi',
            'battery' => 'N/A',
            'mode' => 'Automatique',
            'current_temp' => null,
            'target_temp' => null,
            'last_interaction' => 'Aujourd\'hui, 10:00 AM',
        ]);

        ConnectedObject::create([
            'name' => 'Thermostat Intelligent',
            'description' => 'Un thermostat pour réguler la température de votre maison.',
            'category' => 'Climatisation',
            'brand' => 'Nest',
            'type' => 'Thermostat',
            'status' => 'Actif',
            'connectivity' => 'Wi-Fi',
            'battery' => '65%',
            'mode' => 'Automatique',
            'current_temp' => '21°C',
            'target_temp' => '23°C',
            'last_interaction' => 'Aujourd\'hui, 11:15 AM',
        ]);

        ConnectedObject::create([
            'name' => 'Caméra de Sécurité',
            'description' => 'Une caméra connectée pour surveiller votre maison à distance.',
            'category' => 'Sécurité',
            'brand' => 'Arlo',
            'type' => 'Caméra',
            'status' => 'Actif',
            'connectivity' => 'Wi-Fi',
            'battery' => '78%',
            'mode' => 'Surveillance',
            'current_temp' => null,
            'target_temp' => null,
            'last_interaction' => 'Aujourd\'hui, 09:30 AM',
        ]);

        ConnectedObject::create([
            'name' => 'Prise Intelligente',
            'description' => 'Contrôlez vos appareils électriques à distance avec une prise connectée.',
            'category' => 'Électroménager',
            'brand' => 'TP-Link',
            'type' => 'Prise',
            'status' => 'Inactif',
            'connectivity' => 'Bluetooth',
            'battery' => 'N/A',
            'mode' => 'Manuel',
            'current_temp' => null,
            'target_temp' => null,
            'last_interaction' => 'Hier, 18:45',
        ]);

        ConnectedObject::create([
            'name' => 'Sonorisation Intelligente',
            'description' => 'Un système de sonorisation connectée pour votre maison.',
            'category' => 'Audio',
            'brand' => 'Sonos',
            'type' => 'Haut-parleur',
            'status' => 'Actif',
            'connectivity' => 'Wi-Fi',
            'battery' => 'N/A',
            'mode' => 'Automatique',
            'current_temp' => null,
            'target_temp' => null,
            'last_interaction' => 'Aujourd\'hui, 14:00',
        ]);
    }
}
