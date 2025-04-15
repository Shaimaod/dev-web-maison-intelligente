@extends('layouts.user')

@section('title', 'Tableau de bord')

@section('content')
<div class="container py-4">
    <!-- Affichage des messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Section Consommation d'énergie -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Consommation d'énergie journalière
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Consommation actuelle</span>
                        <span class="h4 mb-0">{{ number_format($totalEnergyConsumption, 1) }} kW</span>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $percentUsed = min(100, ($totalEnergyConsumption / ($energyGoal ?: 1)) * 100);
                            $progressClass = $percentUsed < 75 ? 'bg-success' : ($percentUsed < 90 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                             style="width: {{ $percentUsed }}%" 
                             aria-valuenow="{{ $percentUsed }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ round($percentUsed) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Objectif : {{ number_format($energyGoal, 1) }} kW/jour</small>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#setEnergyGoalModal">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </button>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted fst-italic">
                            <i class="fas fa-info-circle me-1"></i>
                            Cette valeur représente votre consommation quotidienne basée sur vos données hebdomadaires.
                            Les appareils ci-dessous montrent leur consommation hebdomadaire ({{ number_format($topEnergyObjects->sum('energy_consumption'), 1) }} kW).
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-info"></i>Économies réalisées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Ce mois-ci</span>
                        <span class="h4 mb-0 {{ $energySavingsPercent > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $energySavingsPercent > 0 ? '+' : '' }}{{ number_format($energySavingsPercent, 1) }}%
                        </span>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $savingsWidth = min(100, abs($energySavingsPercent));
                            $savingsClass = $energySavingsPercent > 0 ? 'bg-success' : 'bg-danger';
                        @endphp
                        <div class="progress-bar {{ $savingsClass }}" role="progressbar" 
                             style="width: {{ $savingsWidth }}%" 
                             aria-valuenow="{{ $savingsWidth }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ round($savingsWidth) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Par rapport au mois dernier</small>
                        <a href="{{ route('energy.history') }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-history me-1"></i>Historique
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des appareils les plus énergivores -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2 text-danger"></i>Appareils les plus énergivores
                    </h5>
                    <span class="badge bg-info text-white">Consommation hebdomadaire</span>
                </div>
                <div class="card-body">
                    @if(count($topEnergyObjects) > 0)
                        <div class="row">
                            @foreach($topEnergyObjects as $object)
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center p-3 border rounded shadow-sm">
                                    <div class="flex-shrink-0">
                                        @if($object->image)
                                            <img src="{{ asset('storage/' . $object->image) }}" alt="{{ $object->name }}" class="rounded" width="50" height="50">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-plug text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0">{{ $object->name }}</h6>
                                        <div class="text-muted small">{{ number_format($object->energy_consumption, 1) }} kW</div>
                                    </div>
                                    <span class="badge {{ $object->status === 'Actif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $object->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('energy.details') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i>Voir tous les appareils
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-plug fa-3x mb-3"></i>
                            <p>Aucun appareil avec des données de consommation n'est disponible.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Niveau utilisateur -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Votre niveau
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-3">{{ Auth::user()->level }}</h4>
                            @if($nextLevelInfo['name'])
                                <div class="progress mb-2">
                                    <div class="progress-bar progress-bar-striped" 
                                         role="progressbar" 
                                         style="width: {{ $nextLevelInfo['progress_percent'] }}%" 
                                         aria-valuenow="{{ $nextLevelInfo['current_points'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="{{ $nextLevelInfo['points_needed'] }}">
                                        {{ round($nextLevelInfo['progress_percent']) }}%
                                    </div>
                                </div>
                                <p class="text-muted mb-0">
                                    {{ $nextLevelInfo['points_needed'] - $nextLevelInfo['current_points'] }} points pour atteindre le niveau {{ $nextLevelInfo['name'] }}
                                </p>
                            @else
                                <div class="progress mb-2">
                                    <div class="progress-bar progress-bar-striped bg-success" 
                                         role="progressbar" 
                                         style="width: 100%" 
                                         aria-valuenow="100" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        100%
                                    </div>
                                </div>
                                <p class="text-muted mb-0">Vous avez atteint le niveau maximum !</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="display-4 mb-3">{{ Auth::user()->points }}</div>
                            <p class="text-muted mb-0">Points d'expérience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(!in_array(Auth::user()->level, ['avancé', 'expert']) && Auth::user()->role !== 'admin')
    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle me-2"></i>
        Vous pouvez consulter tous les objets connectés. Pour pouvoir les modifier, vous devez atteindre le niveau <strong>avancé</strong>.
        <div class="mt-2">
            <strong>Votre niveau actuel :</strong> {{ Auth::user()->level }}
            <div class="progress mt-1" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: {{ $nextLevelInfo['progress_percent'] }}%" 
                     aria-valuenow="{{ $nextLevelInfo['progress_percent'] }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100"></div>
            </div>
            <small class="text-muted">{{ $nextLevelInfo['points_remaining'] }} points restants pour atteindre le niveau suivant</small>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour définir l'objectif de consommation d'énergie -->
<div class="modal fade" id="setEnergyGoalModal" tabindex="-1" aria-labelledby="setEnergyGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setEnergyGoalModalLabel">Définir un objectif de consommation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('energy.set-goal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="energyGoal" class="form-label">Objectif de consommation quotidienne (kW)</label>
                        <input type="number" class="form-control" id="energyGoal" name="energyGoal" 
                               value="{{ $energyGoal }}" min="0.1" step="0.1" required>
                        <div class="form-text">
                            Définissez un objectif réaliste en fonction de vos habitudes de consommation.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-hover {
    transition: transform 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-5px);
}

.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.object-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.object-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-img-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.object-card:hover .card-img-container img {
    transform: scale(1.05);
}

.object-status {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.object-status.actif {
    background-color: #28a745;
    color: white;
}

.object-status.inactif {
    background-color: #dc3545;
    color: white;
}

.object-details {
    margin-top: 1rem;
}

.object-details .badge {
    margin-right: 0.5rem;
}

.empty-state {
    padding: 2rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}

.progress {
    height: 1rem;
    border-radius: 0.5rem;
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
}
</style>
@endsection
