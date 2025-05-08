<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Models\ObjectUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur de gestion de l'utilisation des objets connectés
 * 
 * Ce contrôleur permet de suivre et d'analyser l'utilisation
 * des objets connectés, notamment leur consommation énergétique,
 * leur efficacité et leurs besoins de maintenance.
 */
class ObjectUsageController extends Controller
{
    /**
     * Affiche l'historique d'utilisation d'un objet connecté
     * 
     * @param ConnectedObject $object L'objet dont on veut voir l'utilisation
     * @return \Illuminate\View\View
     */
    public function index(ConnectedObject $object)
    {
        // Vérifier si l'utilisateur a accès à l'objet
        if (!Auth::user()->can('view', $object)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cet objet.');
        }

        // Récupérer les données d'utilisation
        $usages = $object->usages()
            ->orderBy('recorded_at', 'desc')
            ->paginate(10);

        // Calculer les statistiques
        $stats = [
            'total_energy' => $object->usages()->sum('energy_consumption'),
            'avg_efficiency' => $object->usages()->avg('efficiency_score'),
            'maintenance_count' => $object->usages()->where('maintenance_needed', true)->count(),
        ];

        return view('object-usage.index', compact('object', 'usages', 'stats'));
    }

    /**
     * Enregistre une nouvelle utilisation d'un objet connecté
     * 
     * @param Request $request La requête contenant les données d'utilisation
     * @param ConnectedObject $object L'objet concerné
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, ConnectedObject $object)
    {
        // Vérifier si l'utilisateur a accès à l'objet
        if (!Auth::user()->can('update', $object)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cet objet.');
        }

        // Valider les données
        $validated = $request->validate([
            'energy_consumption' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|string',
            'efficiency_score' => 'required|numeric|min:0|max:100',
            'maintenance_needed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Ajouter la date d'enregistrement
        $validated['recorded_at'] = now();

        // Créer l'enregistrement
        $object->usages()->create($validated);

        return redirect()->back()->with('success', 'Données d\'utilisation enregistrées avec succès.');
    }

    /**
     * Génère un rapport détaillé d'utilisation d'un objet
     * Inclut les statistiques quotidiennes, hebdomadaires et mensuelles
     * 
     * @param ConnectedObject $object L'objet pour lequel générer le rapport
     * @return \Illuminate\View\View
     */
    public function report(ConnectedObject $object)
    {
        // Vérifier si l'utilisateur a accès à l'objet
        if (!Auth::user()->can('view', $object)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cet objet.');
        }

        // Récupérer les données pour le rapport
        $dailyStats = $object->usages()
            ->selectRaw('DATE(recorded_at) as date, 
                        SUM(energy_consumption) as total_energy,
                        AVG(efficiency_score) as avg_efficiency,
                        COUNT(*) as usage_count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $weeklyStats = $object->usages()
            ->selectRaw('YEARWEEK(recorded_at) as week,
                        SUM(energy_consumption) as total_energy,
                        AVG(efficiency_score) as avg_efficiency,
                        COUNT(*) as usage_count')
            ->groupBy('week')
            ->orderBy('week', 'desc')
            ->get();

        return view('object-usage.report', compact('object', 'dailyStats', 'weeklyStats'));
    }
}