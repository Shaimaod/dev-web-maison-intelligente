<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use Illuminate\Http\Request;

class ConnectedObjectController extends Controller
{
    // Méthode pour afficher la vue de recherche des objets connectés
    public function index()
    {
        return view('freetour');  // Assurez-vous que la vue 'freetour' existe
    }

    // Méthode pour récupérer les objets filtrés via l'API
    public function getObjects(Request $request)
    {
        $query = $request->query('query');
        $category = $request->query('category');
    
        $objects = \App\Models\ConnectedObject::query();
    
        if ($query) {
            $objects->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }
    
        if ($category) {
            $objects->where('category', $category);
        }
    
        return response()->json($objects->get());
    }
    
}





