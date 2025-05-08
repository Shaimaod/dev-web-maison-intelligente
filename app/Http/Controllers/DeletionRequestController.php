<?php

namespace App\Http\Controllers;

use App\Models\ConnectedObject;
use App\Models\DeletionRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur de gestion des demandes de suppression d'objets
 * 
 * Ce contrôleur permet de gérer le processus de demande et 
 * d'approbation pour la suppression d'objets connectés. Les 
 * utilisateurs peuvent demander la suppression d'un objet, et 
 * les administrateurs peuvent approuver ou rejeter ces demandes.
 */
class DeletionRequestController extends Controller
{
    /**
     * Affiche la liste des demandes de suppression
     * Accessible uniquement aux administrateurs
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = DeletionRequest::with(['object', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.deletion-requests.index', compact('requests'));
    }

    /**
     * Affiche les détails d'une demande de suppression spécifique
     * 
     * @param DeletionRequest $request La demande à afficher
     * @return \Illuminate\View\View
     */
    public function show(DeletionRequest $request)
    {
        return view('admin.deletion-requests.show', compact('request'));
    }

    /**
     * Traite une demande de suppression (approuver ou rejeter)
     * 
     * @param Request $httpRequest La requête HTTP contenant l'action à effectuer
     * @param DeletionRequest $deletionRequest La demande de suppression à traiter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $httpRequest, DeletionRequest $deletionRequest)
    {
        $action = $httpRequest->input('action');
        
        Log::info('Traitement de la demande de suppression', [
            'action' => $action,
            'deletion_request_id' => $deletionRequest->id,
            'object_id' => $deletionRequest->object_id
        ]);

        if ($action === 'approve') {
            try {
                // Vérifier si l'objet existe
                $objectExists = DB::table('connected_objects')->where('id', $deletionRequest->object_id)->exists();
                Log::info('Vérification de l\'existence de l\'objet', ['exists' => $objectExists]);
                
                // Récupérer les informations de l'objet avant sa suppression
                $object = ConnectedObject::find($deletionRequest->object_id);
                $objectName = $object ? $object->name : 'Objet inconnu';
                
                // Supprimer l'objet connecté
                $objectDeleted = DB::table('connected_objects')->where('id', $deletionRequest->object_id)->delete();
                Log::info('Suppression de l\'objet', ['deleted' => $objectDeleted]);
                
                // Supprimer la demande
                $requestDeleted = DB::table('deletion_requests')->where('id', $deletionRequest->id)->delete();
                Log::info('Suppression de la demande', ['deleted' => $requestDeleted]);
                
                // Enregistrer l'activité
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action_type' => 'object_deletion_approved',
                    'description' => 'Demande de suppression d\'objet approuvée',
                    'details' => [
                        'object_id' => $deletionRequest->object_id,
                        'object_name' => $objectName,
                        'request_id' => $deletionRequest->id,
                        'requested_by' => $deletionRequest->user_id
                    ]
                ]);
                
                return redirect()->route('admin.deletion-requests.index')
                    ->with('success', 'La demande a été approuvée et l\'objet a été supprimé.');
                
            } catch (\Exception $e) {
                Log::error('Erreur lors de la suppression', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
            }
        } elseif ($action === 'reject') {
            try {
                // Récupérer les informations de l'objet avant de supprimer la demande
                $object = ConnectedObject::find($deletionRequest->object_id);
                $objectName = $object ? $object->name : 'Objet inconnu';
                
                // Supprimer la demande rejetée
                $requestDeleted = DB::table('deletion_requests')->where('id', $deletionRequest->id)->delete();
                Log::info('Suppression de la demande rejetée', ['deleted' => $requestDeleted]);
                
                // Enregistrer l'activité
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action_type' => 'object_deletion_rejected',
                    'description' => 'Demande de suppression d\'objet rejetée',
                    'details' => [
                        'object_id' => $deletionRequest->object_id,
                        'object_name' => $objectName,
                        'request_id' => $deletionRequest->id,
                        'requested_by' => $deletionRequest->user_id
                    ]
                ]);
                
                return redirect()->route('admin.deletion-requests.index')
                    ->with('success', 'La demande a été rejetée.');
            } catch (\Exception $e) {
                Log::error('Erreur lors de la suppression de la demande rejetée', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('error', 'Action non valide.');
    }

    /**
     * Crée une nouvelle demande de suppression pour un objet
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestDeletion(Request $request, $id)
    {
        try {
            $object = ConnectedObject::findOrFail($id);

            if (!Auth::user()->canRequestObjectDeletion()) {
                return response()->json(['message' => 'Vous n\'êtes pas autorisé à demander la suppression de cet objet.'], 403);
            }

            $existingRequest = DeletionRequest::where('object_id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json(['message' => 'Une demande de suppression est déjà en attente pour cet objet.'], 400);
            }

            DeletionRequest::create([
                'object_id' => $object->id,
                'user_id' => Auth::id(),
                'reason' => $request->input('reason', 'Demande utilisateur'),
                'status' => 'pending',
            ]);

            return response()->json(['message' => 'Demande de suppression envoyée avec succès.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Objet non trouvé.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur inattendue est survenue.'], 500);
        }
    }
}