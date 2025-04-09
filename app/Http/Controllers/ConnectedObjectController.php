<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use Illuminate\Http\Request;

class ConnectedObjectController extends Controller
{
    // Méthode pour afficher la vue de recherche des objets connectés pour freetour
    public function index()
    {
        return view('freetour');  // Vue pour freetour
    }

    // Méthode pour afficher la vue de recherche des objets connectés pour dashboard.connected
    public function dashboardConnected()
    {
        return view('dashboard.connected');  // Vue pour dashboard.connected
    }

    // Dans ConnectedObjectController.php
    public function showConnectedObjects()
    {
        return view('connected-objects');  // La vue sera connectée à Vue.js
    }

    // Méthode pour récupérer les objets filtrés via l'API
    public function getObjects(Request $request)
    {
        try {
            // Validation des paramètres
            $validated = $request->validate([
                'query' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',  // Validation de la pagination
            ]);

            $query = $request->query('query');
            $category = $request->query('category');
            $brand = $request->query('brand');
            $status = $request->query('status');
            $perPage = $request->query('per_page', 10); // Nombre d'objets par page, défaut à 10

            // Construire la requête pour récupérer les objets filtrés
            $objects = ConnectedObject::query();

            if ($query) {
                $objects->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
                });
            }

            if ($category) {
                $objects->where('category', $category);
            }

            if ($brand) {
                $objects->where('brand', $brand);
            }

            if ($status) {
                $objects->where('status', $status);
            }

            // Pagination
            $objectsPaginated = $objects->paginate($perPage);

            // Retourner les résultats paginés
            return response()->json($objectsPaginated);
            
        } catch (\Exception $e) {
            // Capture d'erreur, affichage du message d'erreur
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}


