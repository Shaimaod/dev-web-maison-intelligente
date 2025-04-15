<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectModel;

class ObjectController extends Controller
{
    // ...existing code...

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

    // ...existing code...
}