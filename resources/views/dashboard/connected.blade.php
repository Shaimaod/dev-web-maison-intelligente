@extends('layouts.user')

@section('title', 'Tableau de bord')

@section('content')
<div class="container py-4">
    <!-- Section Consommation d'énergie -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Consommation d'énergie
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Consommation actuelle</span>
                        <span class="h4 mb-0">2.5 kW</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 45%">45%</div>
                    </div>
                    <small class="text-muted mt-2 d-block">Objectif : 5.5 kW/jour</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Économies réalisées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Ce mois-ci</span>
                        <span class="h4 mb-0 text-success">15%</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 15%">15%</div>
                    </div>
                    <small class="text-muted mt-2 d-block">Par rapport au mois dernier</small>
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
