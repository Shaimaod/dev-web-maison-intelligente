<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Models\ActivityLog;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultObjectController extends Controller
{
    /**
     * Crée un objet par défaut et redirige vers la page d'édition
     */
    public function createDefault(Request $request)
    {
        // Vérifier si l'utilisateur a au moins une maison
        $house = House::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id());
        })->first();

        // Si l'utilisateur n'a pas de maison, en créer une par défaut
        if (!$house) {
            $house = House::create([
                'name' => 'Maison de ' . Auth::user()->name,
                'address' => 'Adresse non spécifiée',
                'description' => 'Maison créée automatiquement'
            ]);
            
            // Associer l'utilisateur à cette maison
            $user = Auth::user();
            $user->house_id = $house->id;
            $user->house_role = 'owner';
            $user->save();
            
            // Enregistrer dans le log d'activité
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'house_created',
                'description' => 'Création automatique d\'une maison',
                'details' => [
                    'house_id' => $house->id,
                    'house_name' => $house->name
                ]
            ]);
        }

        // Créer un objet connecté par défaut
        $object = ConnectedObject::create([
            'name' => 'Nouvel objet',
            'description' => 'Description de l\'objet',
            'category' => 'Éclairage',
            'room' => 'Salon',
            'brand' => 'Marque',
            'type' => 'Ampoule',
            'status' => 'Inactif',
            'connectivity' => 'Wi-Fi',
            'mode' => 'Manuel',
            'is_automated' => false,
            'house_id' => $house->id
        ]);

        // Enregistrer dans le log d'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action_type' => 'object_added',
            'description' => 'Ajout d\'un nouvel objet connecté (modèle par défaut)',
            'details' => [
                'object_id' => $object->id,
                'object_name' => $object->name,
                'object_category' => $object->category
            ]
        ]);

        // Rediriger vers la page d'édition
        return redirect()->route('object.edit', $object->id)
            ->with('success', 'Objet créé avec succès. Vous pouvez maintenant le personnaliser.');
    }
}
