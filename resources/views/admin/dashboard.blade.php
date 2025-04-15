@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="text-uppercase text-primary fw-bold small">Utilisateurs totaux</div>
                    <div class="h5 fw-bold">{{ $users->count() }}</div>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="text-uppercase text-success fw-bold small">Utilisateurs actifs</div>
                    <div class="h5 fw-bold">{{ $users->where('last_login_at', '>=', now()->subDay())->count() }}</div>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-user-check fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="text-uppercase text-info fw-bold small">Emails autorisés</div>
                    <div class="h5 fw-bold">{{ \App\Models\AuthorizedUser::count() }}</div>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-envelope fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="text-uppercase text-warning fw-bold small">Objets connectés</div>
                    <div class="h5 fw-bold">{{ \App\Models\ConnectedObject::count() }}</div>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-plug fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-users me-2"></i>Liste des utilisateurs
        </h6>
        <form class="d-flex" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-light">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                        <th>Dernière connexion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $user->name }}">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</td>
                        <td>
                            @if($user->id !== Auth::id())
                            <div class="btn-group">
                                <a href="{{ route('profile.activity', ['user' => $user->id]) }}" class="btn btn-sm btn-info" title="Voir l'historique">
                                    <i class="fas fa-history"></i>
                                </a>
                                <form action="{{ route('admin.updateRole', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                    <button type="submit" class="btn btn-sm {{ $user->role === 'admin' ? 'btn-warning' : 'btn-success' }}" title="{{ $user->role === 'admin' ? 'Rétrograder' : 'Promouvoir' }}">
                                        <i class="fas {{ $user->role === 'admin' ? 'fa-user-minus' : 'fa-user-plus' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-muted">Vous</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $users->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>
@endsection
