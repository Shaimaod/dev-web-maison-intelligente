@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Administrateur</h1>
    <div class="mb-4">
        <a href="{{ route('authorized-users.index') }}" class="btn btn-success">
            ➕ Gérer les emails autorisés
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">Liste des utilisateurs</div>
        <div class="card-body table-responsive">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4 d-flex">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2" placeholder="Rechercher un utilisateur...">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role ?? 'user' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="d-flex gap-2">
                            @if ($user->id !== Auth::id())
                                <!-- Modifier rôle -->
                                <form action="{{ route('admin.updateRole', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                    <button class="btn btn-sm btn-warning" type="submit">
                                        {{ $user->role === 'admin' ? 'Rétrograder' : 'Promouvoir' }}
                                    </button>
                                </form>

                                <!-- Supprimer utilisateur -->
                                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            @else
                                <em>Vous</em>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">Aucun utilisateur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $users->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>
@endsection
