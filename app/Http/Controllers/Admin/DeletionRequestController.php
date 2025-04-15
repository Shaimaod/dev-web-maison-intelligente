<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeletionRequest;
use App\Models\ConnectedObject;
use App\Models\ActivityLog;
use App\Traits\LogsUserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeletionRequestController extends Controller
{
    use LogsUserActivity;

    /**
     * Afficher la liste des demandes de suppression
     */
    public function index()
    {
        $requests = DeletionRequest::with(['object', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.deletion-requests.index', compact('requests'));
    }

    /**
     * Afficher les détails d'une demande de suppression
     */
    public function show(DeletionRequest $request)
    {
        $request->load(['object', 'user']);
        return view('admin.deletion-requests.show', compact('request'));
    }

    /**
     * Traiter une demande de suppression (approuver ou rejeter)
     */
    public function process(Request $request, DeletionRequest $deletionRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string|max:500'
        ]);

        $deletionRequest->status = $validated['status'];
        $deletionRequest->admin_comment = $validated['admin_comment'];
        $deletionRequest->processed_by = Auth::id();
        $deletionRequest->processed_at = now();
        $deletionRequest->save();

        // Si la demande est approuvée, supprimer l'objet
        if ($validated['status'] === 'approved') {
            $object = $deletionRequest->object;
            $objectName = $object->name;
            $object->delete();

            // Enregistrer l'activité
            $this->logActivity(
                'object_deleted',
                'Objet connecté supprimé suite à une demande',
                [
                    'object_id' => $object->id,
                    'object_name' => $objectName,
                    'request_id' => $deletionRequest->id
                ]
            );
        } else {
            // Enregistrer l'activité de rejet
            $this->logActivity(
                'deletion_request_rejected',
                'Demande de suppression rejetée',
                [
                    'object_id' => $deletionRequest->object_id,
                    'object_name' => $deletionRequest->object->name,
                    'request_id' => $deletionRequest->id
                ]
            );
        }

        return redirect()->route('admin.deletion-requests.index')
            ->with('success', 'La demande de suppression a été traitée avec succès.');
    }
}
