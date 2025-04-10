@extends('layouts.admin')

@section('title', 'Gestion de l\'expérience')

@section('content')
<div class="row">
    <!-- Configuration des points -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>Configuration des points
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.experience.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Points pour l'ajout d'un objet</label>
                        <input type="number" class="form-control" name="object_added" value="{{ $points['object_added'] }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points pour la mise à jour du profil</label>
                        <input type="number" class="form-control" name="profile_update" value="{{ $points['profile_update'] }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points pour la connexion</label>
                        <input type="number" class="form-control" name="login" value="{{ $points['login'] }}" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Configuration des niveaux -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Configuration des niveaux
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.experience.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Points pour le niveau Débutant</label>
                        <input type="number" class="form-control" name="beginner" value="{{ $levels['beginner'] }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points pour le niveau Intermédiaire</label>
                        <input type="number" class="form-control" name="intermediate" value="{{ $levels['intermediate'] }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points pour le niveau Avancé</label>
                        <input type="number" class="form-control" name="advanced" value="{{ $levels['advanced'] }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points pour le niveau Expert</label>
                        <input type="number" class="form-control" name="expert" value="{{ $levels['expert'] }}" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Gestion des points des utilisateurs
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Points actuels</th>
                        <th>Niveau</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle me-2" width="32" height="32" alt="{{ $user->name }}">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->points }}</td>
                        <td>
                            <span class="badge bg-primary">{{ ucfirst($user->level) }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.experience.update-user', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm" name="points" value="{{ $user->points }}" min="0" style="width: 100px;">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 