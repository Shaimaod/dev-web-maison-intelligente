<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Models\ActivityLog;
use App\Traits\LogsUserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // Vérifier si des paramètres de recherche sont fournis
        $searchDetails = [
            'query' => $request->input('query'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'status' => $request->input('status'),
            'per_page' => $request->input('per_page', 12)
        ];

        // Filtrer les valeurs nulles ou vides
        $searchDetails = array_filter($searchDetails, function($value) {
            return !is_null($value) && $value !== '';
        });

        // Ne pas enregistrer de log si tous les champs de recherche sont vides
        if (auth()->check() && !empty($searchDetails) && count($searchDetails) > 1) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'object_search',
                'description' => 'Recherche d\'objets connectés',
                'details' => $searchDetails
            ]);
        }

        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = ConnectedObject::query();

        if ($request->filled('query')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('query') . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $request->input('per_page', 12);
        $objects = $query->paginate($perPage);

        return response()->json($objects);
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
     * Affiche la page d'édition d'un objet connecté
     */
    public function edit($id)
    {
        $object = ConnectedObject::findOrFail($id);
        return view('object.edit', compact('object'));
    }

    /**
     * Met à jour un objet connecté
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|in:Actif,Inactif',
            'connectivity' => 'required|string|max:255',
            'battery' => 'nullable|integer|min:0|max:100',
            'mode' => 'nullable|string|max:255',
            'is_automated' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $object = ConnectedObject::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/connected_objects', $imageName);
            $data['image'] = 'connected_objects/' . $imageName;
        }

        // Récupérer les anciennes valeurs pour comparaison
        $oldValues = $object->only(array_keys($data));

        // Mettre à jour l'objet
        $object->update($data);

        // Enregistrer les modifications dans l'historique utilisateur
        $changes = [];
        foreach ($data as $key => $value) {
            if (isset($oldValues[$key]) && $oldValues[$key] != $value) {
                $changes[] = ucfirst($key) . ': "' . $oldValues[$key] . '" → "' . $value . '"';
            }
        }

        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'object_updated',
                'description' => 'Modification d\'un objet connecté',
                'details' => [
                    'object_id' => $id,
                    'changes' => implode(', ', $changes),
                ],
            ]);
        }

        return redirect()->route('object.show', $object->id)
            ->with('success', 'Objet connecté mis à jour avec succès.');
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


