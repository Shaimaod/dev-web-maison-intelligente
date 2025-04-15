@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Demandes de suppression d'objets</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tableau des demandes -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Objet</th>
                            <th>Utilisateur</th>
                            <th>Date de la demande</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>
                                    <a href="{{ route('object.show', $request->object) }}" class="text-decoration-none">
                                        {{ $request->object->name }}
                                    </a>
                                </td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge bg-success">Approuvée</span>
                                    @else
                                        <span class="badge bg-danger">Rejetée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.deletion-requests.show', $request) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune demande de suppression.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.5rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.table td {
    vertical-align: middle;
}

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}
</style>
@endsection 