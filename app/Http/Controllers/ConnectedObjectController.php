<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Traits\LogsUserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectedObjectController extends Controller
{
    use LogsUserActivity;

    // Méthode pour afficher la vue de recherche des objets connectés pour freetour
    public function index()
    {
        return view('freetour');  // Vue pour freetour
    }

    // Méthode pour afficher la vue de recherche des objets connectés pour dashboard.connected
    public function dashboardConnected()
    {
        $user = Auth::user();
        $connectedObjects = $user->connectedObjects;
        $nextLevelInfo = $user->getNextLevelInfo();

        return view('dashboard.connected', compact('user', 'connectedObjects', 'nextLevelInfo'));
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
            \Log::info('Début de getObjects', ['request' => $request->all()]);
            
            // Validation des paramètres
            $validated = $request->validate([
                'query' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $query = $request->query('query');
            $category = $request->query('category');
            $brand = $request->query('brand');
            $status = $request->query('status');
            $perPage = $request->query('per_page', 10);

            \Log::info('Paramètres validés', [
                'query' => $query,
                'category' => $category,
                'brand' => $brand,
                'status' => $status,
                'per_page' => $perPage
            ]);

            // Construire la requête pour récupérer tous les objets
            $objects = ConnectedObject::query();
            \Log::info('Nombre total d\'objets avant filtres', ['count' => $objects->count()]);

            // Appliquer les filtres
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

            \Log::info('Nombre d\'objets après filtres', ['count' => $objects->count()]);

            // Pagination
            $objectsPaginated = $objects->paginate($perPage);
            \Log::info('Résultats paginés', [
                'total' => $objectsPaginated->total(),
                'per_page' => $objectsPaginated->perPage(),
                'current_page' => $objectsPaginated->currentPage(),
                'last_page' => $objectsPaginated->lastPage()
            ]);

            // Retourner les résultats paginés
            return response()->json($objectsPaginated);
            
        } catch (\Exception $e) {
            \Log::error('Erreur dans getObjects', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour ajouter un nouvel objet connecté
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'connectivity' => 'required|string|max:255',
            'battery' => 'nullable|integer|min:0|max:100',
            'mode' => 'required|string|max:255',
            'current_temp' => 'nullable|numeric',
            'target_temp' => 'nullable|numeric',
            'last_interaction' => 'nullable|date',
            'settings' => 'nullable|json',
            'schedule' => 'nullable|json',
            'is_automated' => 'boolean'
        ]);

        $data = $request->all();
        $data['house_id'] = Auth::user()->house_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/connected_objects', $imageName);
            $data['image'] = 'connected_objects/' . $imageName;
        }

        $connectedObject = ConnectedObject::create($data);

        return redirect()->route('dashboard.connected')
            ->with('success', 'Objet connecté créé avec succès.');
    }

    /**
     * Affiche les détails d'un objet connecté
     */
    public function show($id)
    {
        $object = ConnectedObject::findOrFail($id);
        return view('object.show', compact('object'));
    }

    /**
     * Met à jour un objet connecté
     */
    public function update(Request $request, $id)
    {
        try {
            $object = ConnectedObject::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'nullable|string|max:255',
                'mode' => 'nullable|string|max:255',
                'brightness' => 'nullable|integer|min:0|max:100',
                'color' => 'nullable|string|max:7',
                'surveillance_mode' => 'nullable|string|max:255',
                'sensitivity' => 'nullable|integer|min:1|max:10',
                'volume' => 'nullable|integer|min:0|max:100',
                'audio_source' => 'nullable|string|max:255',
                'target_temp' => 'nullable|string|max:255',
            ]);

            $object->update($validated);

            // Enregistrement du log d'activité
            $this->logActivity(
                'object_updated',
                'Objet connecté mis à jour',
                [
                    'object_id' => $object->id,
                    'object_name' => $object->name,
                    'changes' => $validated
                ]
            );

            return response()->json([
                'message' => 'Objet connecté mis à jour avec succès',
                'object' => $object
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'objet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $object = ConnectedObject::findOrFail($id);
            $objectName = $object->name;
            $object->delete();

            // Enregistrement du log d'activité
            $this->logActivity(
                'object_deleted',
                'Objet connecté supprimé',
                [
                    'object_id' => $id,
                    'object_name' => $objectName
                ]
            );

            return response()->json([
                'message' => 'Objet connecté supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'objet',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


