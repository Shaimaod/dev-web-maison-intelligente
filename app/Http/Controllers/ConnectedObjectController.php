<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Models\ActivityLog;
use App\Models\ObjectUsage;
use App\Models\EnergyGoal;
use App\Traits\LogsUserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

/**
 * Contrôleur de gestion des objets connectés
 *
 * Ce contrôleur gère toutes les opérations liées aux objets connectés:
 * - L'affichage et la recherche d'objets
 * - La création et modification d'objets
 * - La suppression d'objets
 * - L'affichage des statistiques et détails d'objets
 * - La gestion des États et paramètres des objets
 */
class ConnectedObjectController extends Controller
{
    use LogsUserActivity;

/**
     * Constructeur définissant les middlewares d'autorisation
     * Vérifie les permissions avant chaque opération de modification
     */
    public function __construct()
    {
        // Appliquer l'autorisation aux actions de modification
        $this->middleware(function ($request, $next) {
            if ($request->is('object/*/edit') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
                $id = $request->route('id');
                $object = ConnectedObject::findOrFail($id);
                
                if ($request->isMethod('DELETE')) {
                    $this->authorize('delete', $object);
                } else {
                    $this->authorize('update', $object);
                }
            }
            
            return $next($request);
        })->except(['index', 'dashboardConnected', 'showConnectedObjects', 'getObjects', 'show']);
    }

    /**
     * Affiche la page de recherche des objets connectés (mode visite libre)
     */
    public function index()
    {
        return view('freetour');  // Vue pour freetour
    }

    /**
     * Affiche le formulaire pour créer un nouvel objet connecté
     */
    public function create()
    {
        $this->authorize('create', ConnectedObject::class);
        return view('connected-objects.create');
    }

    /**
     * Affiche la page du tableau de bord des objets connectés
     * Calcule les statistiques de consommation d'énergie
     */
    public function dashboardConnected()
    {
        $user = Auth::user();
        $connectedObjects = $user->connectedObjects;
        $nextLevelInfo = $user->getNextLevelInfo();

        // Valeurs par défaut en cas de table manquante
        $energyGoal = 8.0; // Changed from 5.5 to 8.0
        $energySavingsPercent = 0;
        
        // Récupérer d'abord les objets les plus énergivores
        $topEnergyObjects = $this->getTopEnergyConsumingObjects($user, 3);
        $totalEnergyConsumption = 0;

        // Vérifier si les tables nécessaires existent avant d'exécuter les requêtes
        if (Schema::hasTable('energy_goals') && Schema::hasTable('object_usages')) {
            try {
                // Récupérer l'objectif de consommation d'énergie de l'utilisateur
                $energyGoalObj = \App\Models\EnergyGoal::where('user_id', $user->id)->first();
                if ($energyGoalObj) {
                    $energyGoal = $energyGoalObj->daily_goal;
                }

                // Calculer la consommation d'énergie actuelle
                // Si on a des données réelles dans topEnergyObjects, utiliser la somme
                if ($topEnergyObjects->isNotEmpty() && $topEnergyObjects->first()->energy_consumption > 0) {
                    // Utiliser les données réelles avec un facteur d'ajustement pour simuler la consommation quotidienne
                    $totalEnergyConsumption = $topEnergyObjects->sum('energy_consumption') * 0.35;
                } else {
                    // Sinon, utiliser la méthode de calcul déterministe
                    $totalEnergyConsumption = $this->simulateTotalDeterministicConsumption($user);
                }
                
                // Calculer les économies réalisées (en pourcentage par rapport au mois dernier)
                $currentMonthConsumption = $this->calculateEnergyConsumption($user, 'month');
                $lastMonthConsumption = $this->calculateEnergyConsumption($user, 'last_month');
                
                if ($lastMonthConsumption > 0) {
                    $energySavingsPercent = (($lastMonthConsumption - $currentMonthConsumption) / $lastMonthConsumption) * 100;
                } else {
                    // Générer une valeur constante pour les économies si pas de données réelles
                    $energySavingsPercent = $this->generateDeterministicSavings($user->id);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors du calcul de la consommation d\'énergie: ' . $e->getMessage());
                // Utiliser les valeurs déterministes en cas d'erreur
                $energySavingsPercent = $this->generateDeterministicSavings($user->id);
                $totalEnergyConsumption = $this->simulateTotalDeterministicConsumption($user);
            }
        } else {
            // Créer des objets simulés pour démonstration si la table n'existe pas encore
            $energySavingsPercent = $this->generateDeterministicSavings($user->id);
            
            // Assurer la cohérence entre la consommation totale et les objets les plus énergivores
            if ($topEnergyObjects->isNotEmpty()) {
                // La consommation totale est proportionnelle à la somme des consommations des objets
                $totalEnergyConsumption = $topEnergyObjects->sum('energy_consumption') * 0.4;
            } else {
                $totalEnergyConsumption = $this->simulateTotalDeterministicConsumption($user);
            }
        }

        return view('dashboard.connected', compact(
            'user', 
            'connectedObjects', 
            'nextLevelInfo',
            'totalEnergyConsumption',
            'energyGoal',
            'energySavingsPercent',
            'topEnergyObjects'
        ));
    }

    /**
     * Génère un pourcentage d'économies d'énergie déterministe basé sur l'ID de l'utilisateur
     */
    private function generateDeterministicSavings($userId)
    {
        // Utiliser l'ID utilisateur pour générer une valeur constante
        $seed = ($userId * 17) % 100; // Donne une valeur entre 0 et 99
        
        // Convertir en pourcentage entre -20% et +30%
        return -20 + (($seed / 100) * 50);
    }
    
    /**
     * Génère une consommation d'énergie déterministe pour un objet
     */
    private function generateDeterministicEnergyConsumption($object)
    {
        // Utiliser l'ID comme seed pour une valeur constante
        $seed = $object->id;
        
        // Calculer un facteur basé sur l'ID (sera toujours le même)
        $factor = (($seed * 13) % 100) / 100; // Valeur entre 0 et 1
        
        // Déterminer la consommation de base en fonction de la catégorie
        $baseConsumption = 0;
        switch ($object->category) {
            case 'Climatisation':
                $baseConsumption = 8.5 + ($factor * 3);
                break;
            case 'Électroménager':
                $baseConsumption = 6.2 + ($factor * 2);
                break;
            case 'Éclairage':
                $baseConsumption = 2.1 + ($factor * 1);
                break;
            case 'Sécurité':
                $baseConsumption = 3.0 + ($factor * 1.5);
                break;
            case 'Audio':
                $baseConsumption = 4.0 + ($factor * 1.8);
                break;
            default:
                $baseConsumption = 4.5 + ($factor * 2);
        }
        
        // Ajuster en fonction du statut (toujours le même ajustement)
        if ($object->status !== 'Actif') {
            $baseConsumption *= 0.1; // 10% si inactif
        }
        
        return $baseConsumption;
    }
    
    /**
     * Récupère les objets les plus énergivores avec des valeurs déterministes
     */
    private function getDeterministicTopObjects($user, $limit = 3)
    {
        $objects = $user->connectedObjects;
        
        if ($objects->isEmpty()) {
            return collect();
        }
        
        // Assigner des consommations déterministes à tous les objets
        foreach ($objects as $object) {
            $object->energy_consumption = $this->generateDeterministicEnergyConsumption($object);
        }
        
        // Trier et prendre les premiers
        return $objects->sortByDesc('energy_consumption')->take($limit);
    }
    
    /**
     * Calcule une consommation totale déterministe pour l'utilisateur
     * en s'assurant qu'elle est cohérente avec la somme des objets affichés
     */
    private function simulateTotalDeterministicConsumption($user)
    {
        $objects = $user->connectedObjects;
        
        if ($objects->isEmpty()) {
            // Valeur basée sur l'ID utilisateur si pas d'objets
            return (($user->id * 31) % 10) + 2.5;
        }
        
        // Calculer la consommation en utilisant tous les objets
        $total = 0;
        foreach ($objects as $object) {
            // Utiliser l'intégralité de la consommation pour maintenir la cohérence
            $total += $this->generateDeterministicEnergyConsumption($object);
        }
        
        // Réduire légèrement le total pour simuler une consommation quotidienne réaliste
        return $total * 0.4;
    }
    
    /**
     * Récupère les objets les plus énergivores
     */
    private function getTopEnergyConsumingObjects($user, $limit = 3)
    {
        $objects = $user->connectedObjects()->pluck('id');
        
        // Si aucun objet, retourner une collection vide
        if ($objects->isEmpty()) {
            return collect();
        }
        
        $hasData = false;
        $topObjects = null;
        
        try {
            // Vérifier si la table existe et contient des données
            if (Schema::hasTable('object_usages')) {
                $topObjectIds = ObjectUsage::whereIn('object_id', $objects)
                    ->where('recorded_at', '>=', Carbon::now()->subWeek())
                    ->selectRaw('object_id, SUM(energy_consumption) as total_energy')
                    ->groupBy('object_id')
                    ->orderBy('total_energy', 'desc')
                    ->limit($limit)
                    ->pluck('object_id');
                    
                $topObjects = ConnectedObject::whereIn('id', $topObjectIds)->get();
                
                // Vérifier s'il y a des données réelles
                if ($topObjects->count() > 0) {
                    // Ajouter la consommation d'énergie à chaque objet
                    foreach ($topObjects as $object) {
                        $object->energy_consumption = ObjectUsage::where('object_id', $object->id)
                            ->where('recorded_at', '>=', Carbon::now()->subWeek())
                            ->sum('energy_consumption');
                            
                        if ($object->energy_consumption > 0) {
                            $hasData = true;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des objets énergivores: ' . $e->getMessage());
            return $this->getDeterministicTopObjects($user, $limit);
        }
        
        // Si aucune donnée réelle trouvée, utiliser des données déterministes
        if (!$hasData) {
            return $this->getDeterministicTopObjects($user, $limit);
        }
        
        return $topObjects;
    }
    
    /**
     * Calcule la consommation d'énergie pour une période donnée
     * 
     * @param User $user
     * @param string $period 'day', 'week', 'month', 'last_month'
     * @return float
     */
    private function calculateEnergyConsumption($user, $period = 'day')
    {
        $objects = $user->connectedObjects()->pluck('id');
        
        // Si aucun objet, retourner 0
        if ($objects->isEmpty()) {
            return 0;
        }
        
        $query = ObjectUsage::whereIn('object_id', $objects);
        
        switch ($period) {
            case 'day':
                $query->where('recorded_at', '>=', Carbon::now()->subDay());
                break;
            case 'week':
                $query->where('recorded_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('recorded_at', '>=', Carbon::now()->startOfMonth());
                break;
            case 'last_month':
                $query->whereBetween('recorded_at', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ]);
                break;
        }
        
        return $query->sum('energy_consumption');
    }

    // Méthode pour afficher la vue des objets connectés
    public function showConnectedObjects()
    {
        return view('connected-objects');  // La vue sera connectée à Vue.js
    }

    /**
     * API pour récupérer les objets connectés (filtrage, recherche...)
     */
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
            
            // Ajouter des points pour la recherche d'objets
            auth()->user()->addPoints('object_search');
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

        // Ajouter un tri explicite pour assurer que les résultats sont dans un ordre cohérent
        // et éviter les duplications lors de la pagination
        $query->orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');

        $perPage = $request->input('per_page', 12);
        
        // Utiliser une valeur unique pour la clé de la page pour éviter les conflits de cache
        $objects = $query->paginate($perPage)->appends($request->except('page'));

        return response()->json($objects);
    }

    /**
     * Enregistre un nouvel objet connecté
*/
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
            'status' => 'required|string|in:Actif,Inactif',  // Assurez-vous que c'est bien in:Actif,Inactif 
            'connectivity' => 'required|string|max:255',
            'battery' => 'nullable|integer|min:0|max:100',
            'mode' => 'nullable|string|max:255',  // Modifié de required à nullable
            'current_temp' => 'nullable|numeric',
            'target_temp' => 'nullable|numeric',
            'last_interaction' => 'nullable|date',
            'settings' => 'nullable|json',
            'schedule' => 'nullable|json',
            'is_automated' => 'boolean|nullable'  // Ajout de nullable
        ]);

        $data = $request->all();
        
        // Si l'utilisateur est connecté et a une maison
        if (Auth::check() && Auth::user()->house_id) {
            $data['house_id'] = Auth::user()->house_id;
        } else {
            // Récupérer une maison par défaut si nécessaire
            $defaultHouse = \App\Models\House::first();
            if ($defaultHouse) {
                $data['house_id'] = $defaultHouse->id;
            } else {
                return redirect()->back()
                    ->with('error', 'Impossible de créer un objet sans maison associée.')
                    ->withInput();
            }
        }

        // Traitement de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/connected_objects', $imageName);
            $data['image'] = 'connected_objects/' . $imageName;
        }

        // Traitement des valeurs booléennes
        $data['is_automated'] = $request->has('is_automated');

        // Création de l'objet
        $connectedObject = ConnectedObject::create($data);

        // Enregistrer dans le log d'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action_type' => 'object_added',
            'description' => 'Ajout d\'un nouvel objet connecté',
            'details' => [
                'object_id' => $connectedObject->id,
                'object_name' => $connectedObject->name,
                'object_category' => $connectedObject->category,
            ]
        ]);

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
        try {
            $object = ConnectedObject::findOrFail($id);
            $this->authorize('update', $object);
            
            // L'autorisation est déjà vérifiée dans le middleware du constructeur
            
            $validated = $request->validate([
                'status' => 'nullable|string|in:Actif,Inactif', // Modifié pour accepter explicitement Actif et Inactif
                'mode' => 'nullable|string|max:255',
                'brightness' => 'nullable|integer|min:0|max:100',
                'color' => 'nullable|string|max:7',
                'surveillance_mode' => 'nullable|string|max:255',
                'sensitivity' => 'nullable|integer|min:1|max:10',
                'volume' => 'nullable|integer|min:0|max:100',
                'audio_source' => 'nullable|string|max:255',
                'target_temp' => 'nullable|string|max:255',
            ]);

            // Récupérer les anciennes valeurs pour la comparaison
            $oldValues = $object->only(array_keys($validated));
            
            $object->update($validated);

            // Préparer les détails des modifications
            $changes = [];
            foreach ($validated as $key => $value) {
                if (isset($oldValues[$key]) && $oldValues[$key] != $value) {
                    $changes[$key] = [
                        'from' => $oldValues[$key],
                        'to' => $value
                    ];
                }
            }

            // Enregistrement du log d'activité avec les détails des modifications
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'object_updated',
                'description' => 'Modification d\'un objet connecté',
                'details' => $changes
            ]);
            
            // Ajouter des points pour la modification d'un objet
            if (!empty($changes) && auth()->check()) {
                auth()->user()->addPoints('object_update');
            }

            return response()->json([
                'message' => 'Objet connecté mis à jour avec succès',
                'object' => $object
            ]);
        } catch (\Exception $e) {
            // Ajouter un log d'erreur pour le débogage
            Log::error('Erreur lors de la mise à jour de l\'objet: ' . $e->getMessage(), [
                'object_id' => $id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'objet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour un objet connecté pour la vue d'édition
     */
    public function updateForEdit(Request $request, $id)
    {
        $object = ConnectedObject::findOrFail($id);
        
        // L'autorisation est déjà vérifiée dans le middleware du constructeur
        
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
                $changes[$key] = [
                    'from' => $oldValues[$key],
                    'to' => $value
                ];
            }
        }

        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'object_updated',
                'description' => 'Modification d\'un objet connecté',
                'details' => [
                    'object_id' => $id,
                    'object_name' => $object->name,
                    'object_category' => $object->category,
                    'changes' => $changes,
                ]
            ]);
            
            // Ajouter des points pour la modification d'un objet
            if (auth()->check()) {
                auth()->user()->addPoints('object_update');
            }
        }

        return redirect()->route('object.edit', $object->id)
            ->with('success', 'Objet connecté mis à jour avec succès.');
    }

/**
     * Supprime un objet connecté
     */
    public function destroy($id)
    {
        try {
            $object = ConnectedObject::findOrFail($id);
            
            // L'autorisation est déjà vérifiée dans le middleware du constructeur
            
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


