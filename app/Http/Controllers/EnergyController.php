<?php

namespace App\Http\Controllers;

use App\Models\EnergyGoal;
use App\Models\ConnectedObject;
use App\Models\ObjectUsage;
use App\Traits\LogsUserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnergyController extends Controller
{
    use LogsUserActivity;

    /**
     * Définir un objectif de consommation d'énergie
     */
    public function setGoal(Request $request)
    {
        $validated = $request->validate([
            'energyGoal' => 'required|numeric|min:0.1'
        ]);

        $user = Auth::user();
        
        try {
            // Mettre à jour ou créer l'objectif
            EnergyGoal::updateOrCreate(
                ['user_id' => $user->id],
                ['daily_goal' => $validated['energyGoal']]
            );
            
            // Log pour debugging
            Log::info('Objectif énergétique mis à jour', [
                'user_id' => $user->id,
                'new_goal' => $validated['energyGoal']
            ]);
            
            return redirect()->route('dashboard.connected')
                ->with('success', 'Objectif de consommation défini avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la définition de l\'objectif énergétique: ' . $e->getMessage());
            return redirect()->route('dashboard.connected')
                ->with('error', 'Une erreur est survenue lors de la définition de l\'objectif: ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'historique de consommation d'énergie
     */
    public function history()
    {
        $user = Auth::user();
        $objects = $user->connectedObjects()->pluck('id');
        
        // Données factices si la table n'existe pas
        $dailyData = collect();
        $monthlyData = collect();
        
        // Vérifier si la table object_usages existe
        if (Schema::hasTable('object_usages') && !$objects->isEmpty()) {
            try {
                // Données quotidiennes (7 derniers jours)
                $dailyData = ObjectUsage::whereIn('object_id', $objects)
                    ->where('recorded_at', '>=', Carbon::now()->subDays(7))
                    ->selectRaw('DATE(recorded_at) as date, SUM(energy_consumption) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                    
                // Données mensuelles (12 derniers mois)
                $monthlyData = ObjectUsage::whereIn('object_id', $objects)
                    ->where('recorded_at', '>=', Carbon::now()->subMonths(12))
                    ->selectRaw('YEAR(recorded_at) as year, MONTH(recorded_at) as month, SUM(energy_consumption) as total')
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
            } catch (\Exception $e) {
                // Loguer l'erreur mais continuer avec des données vides
                \Illuminate\Support\Facades\Log::error('Erreur lors de la récupération de l\'historique: ' . $e->getMessage());
            }
        } else {
            // Créer des données factices pour la démonstration
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dailyData->push([
                    'date' => $date->format('Y-m-d'),
                    'total' => rand(2, 8)
                ]);
            }
            
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyData->push([
                    'year' => $date->year,
                    'month' => $date->month,
                    'total' => rand(50, 200)
                ]);
            }
            
            $dailyData = collect($dailyData);
            $monthlyData = collect($monthlyData);
        }
            
        return view('energy.history', compact('dailyData', 'monthlyData'));
    }

    /**
     * Afficher les détails de consommation par appareil
     */
    public function details()
    {
        $user = Auth::user();
        $objects = $user->connectedObjects;
        
        // Si la table existe et qu'il y a des objets
        if (Schema::hasTable('object_usages') && $objects->isNotEmpty()) {
            try {
                // Ajouter la consommation d'énergie à chaque objet
                foreach ($objects as $object) {
                    // Utiliser exactement le même calcul que dans le tableau de bord
                    $weeklyConsumption = ObjectUsage::where('object_id', $object->id)
                        ->where('recorded_at', '>=', Carbon::now()->subWeek())
                        ->sum('energy_consumption');
                    
                    // Si pas de données réelles, utiliser une valeur déterministe
                    if ($weeklyConsumption <= 0) {
                        // Utiliser la même méthode que dans ConnectedObjectController
                        $weeklyConsumption = $this->generateDeterministicEnergyConsumption($object);
                    }
                    
                    // Stocker la consommation hebdomadaire
                    $object->weekly_consumption = $weeklyConsumption;
                    
                    // Convertir en consommation journalière avec le même facteur (0.35) utilisé dans ConnectedObjectController
                    $object->daily_consumption = $weeklyConsumption * 0.35 / 2.5;
                    
                    // Calcul de la consommation mensuelle (approximation basée sur 4 semaines)
                    $object->monthly_consumption = $weeklyConsumption * 4;
                }
            } catch (\Exception $e) {
                // Loguer l'erreur et utiliser des données simulées
                Log::error('Erreur lors de la récupération des détails: ' . $e->getMessage());
                $this->assignDeterministicConsumption($objects);
            }
        } else {
            // Ajouter des valeurs simulées pour tous les objets
            $this->assignDeterministicConsumption($objects);
        }
        
        // Trier par consommation quotidienne
        $objects = $objects->sortByDesc('daily_consumption');
        
        return view('energy.details', compact('objects'));
    }
    
    /**
     * Génère une consommation d'énergie déterministe basée sur l'ID de l'objet
     * Utilise la même méthode que ConnectedObjectController pour assurer la cohérence
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
     * Assigne des valeurs de consommation déterministes à tous les objets
     * Assure la cohérence avec les calculs du tableau de bord
     */
    private function assignDeterministicConsumption($objects)
    {
        foreach ($objects as $object) {
            // Utiliser la même méthode que dans le tableau de bord
            $weeklyConsumption = $this->generateDeterministicEnergyConsumption($object);
            
            $object->weekly_consumption = $weeklyConsumption;
            $object->daily_consumption = $weeklyConsumption * 0.35 / 2.5; // Même facteur que dans le tableau de bord
            $object->monthly_consumption = $weeklyConsumption * 4;
        }
    }
}
