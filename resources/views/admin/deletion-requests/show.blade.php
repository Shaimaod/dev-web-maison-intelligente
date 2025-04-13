@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Détails de la demande de suppression</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Informations sur l'objet</h5>
                        <p><strong>Nom :</strong> {{ $request->object->name }}</p>
                        <p><strong>Catégorie :</strong> {{ $request->object->category }}</p>
                        <p><strong>Pièce :</strong> {{ $request->object->room }}</p>
                        <p><strong>Marque :</strong> {{ $request->object->brand }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Informations sur la demande</h5>
                        <p><strong>Demandé par :</strong> {{ $request->user->name }}</p>
                        <p><strong>Date de la demande :</strong> {{ $request->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Statut :</strong> 
                            @if($request->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($request->status === 'approved')
                                <span class="badge bg-success">Approuvée</span>
                            @else
                                <span class="badge bg-danger">Rejetée</span>
                            @endif
                        </p>
                        @if($request->reason)
                            <p><strong>Raison :</strong> {{ $request->reason }}</p>
                        @endif
                    </div>

                    @if($request->status === 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.deletion-requests.process', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir approuver cette demande ?')">
                                    <i class="fas fa-check me-2"></i>Approuver
                                </button>
                            </form>

                            <form action="{{ route('admin.deletion-requests.process', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')">
                                    <i class="fas fa-times me-2"></i>Rejeter
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.deletion-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 