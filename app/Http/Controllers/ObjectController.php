<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectModel;

/**
 * Contrôleur de gestion des objets génériques
 * 
 * Ce contrôleur gère les objets génériques du système
 * qui ne sont pas nécessairement des objets connectés.
 * Il s'agit d'un contrôleur secondaire qui complète
 * les fonctionnalités de ConnectedObjectController.
 */
class ObjectController extends Controller
{
    /**
     * Méthodes existantes...
     */

    /**
     * Met à jour les informations d'un objet générique
     * 
     * @param Request $request La requête contenant les nouvelles données
     * @param int $id ID de l'objet à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // Ajoutez d'autres règles de validation si nécessaire
        ]);

        $object = ObjectModel::findOrFail($id);
        $object->name = $request->input('name');
        $object->description = $request->input('description');
        // Mettez à jour d'autres champs si nécessaire
        $object->save();

        return redirect()->route('object.edit', $object->id)->with('status', 'Objet mis à jour avec succès!');
    }

}