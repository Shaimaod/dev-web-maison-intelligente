@extends('layouts.user')

@section('title', 'Détails de consommation')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Détails de consommation par appareil</h1>
                <a href="{{ route('dashboard.connected') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                </a>
            </div>
            
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading">Comprendre votre consommation</h5>
                        <p class="mb-0">Les valeurs présentées ci-dessous sont basées sur les données collectées de vos appareils. Plus vous utilisez la plateforme, plus ces données seront précises.</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plug me-2 text-primary"></i>Appareils connectés
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active" id="btn-day">Jour</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-week">Semaine</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-month">Mois</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Appareil</th>
                                    <th>Catégorie</th>
                                    <th>Statut</th>
                                    <th class="text-end consumption-day">Consommation (jour)</th>
                                    <th class="text-end consumption-week d-none">Consommation (semaine)</th>
                                    <th class="text-end consumption-month d-none">Consommation (mois)</th>
                                    <th class="text-end">Part</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDailyConsumption = $objects->sum('daily_consumption');
                                    $totalWeeklyConsumption = $objects->sum('weekly_consumption');
                                    $totalMonthlyConsumption = $objects->sum('monthly_consumption');
                                @endphp
                                
                                @foreach($objects as $object)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($object->image)
                                                <img src="{{ asset('storage/' . $object->image) }}" alt="{{ $object->name }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-plug text-secondary"></i>
                                                </div>
                                            @endif
                                            <div class="ms-2">
                                                <a href="{{ route('object.show', $object->id) }}" class="text-decoration-none fw-medium">
                                                    {{ $object->name }}
                                                </a>
                                                <div class="text-muted small">{{ $object->room }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $object->category }}</td>
                                    <td>
                                        <span class="badge {{ $object->status === 'Actif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $object->status }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold consumption-day">{{ number_format($object->daily_consumption, 2) }} kW</td>
                                    <td class="text-end fw-bold consumption-week d-none">{{ number_format($object->weekly_consumption, 2) }} kW</td>
                                    <td class="text-end fw-bold consumption-month d-none">{{ number_format($object->monthly_consumption, 2) }} kW</td>
                                    <td class="text-end">
                                        @php
                                            $percentDay = $totalDailyConsumption > 0 ? ($object->daily_consumption / $totalDailyConsumption) * 100 : 0;
                                            $percentWeek = $totalWeeklyConsumption > 0 ? ($object->weekly_consumption / $totalWeeklyConsumption) * 100 : 0;
                                            $percentMonth = $totalMonthlyConsumption > 0 ? ($object->monthly_consumption / $totalMonthlyConsumption) * 100 : 0;
                                        @endphp
                                        <div class="percent-day">{{ number_format($percentDay, 1) }}%</div>
                                        <div class="percent-week d-none">{{ number_format($percentWeek, 1) }}%</div>
                                        <div class="percent-month d-none">{{ number_format($percentMonth, 1) }}%</div>
                                        <div class="progress mt-1" style="height: 6px;">
                                            <div class="progress-bar bg-primary percent-day-bar" role="progressbar" style="width: {{ $percentDay }}%" aria-valuenow="{{ $percentDay }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-primary percent-week-bar d-none" role="progressbar" style="width: {{ $percentWeek }}%" aria-valuenow="{{ $percentWeek }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-primary percent-month-bar d-none" role="progressbar" style="width: {{ $percentMonth }}%" aria-valuenow="{{ $percentMonth }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="fw-bold">Total</td>
                                    <td class="text-end fw-bold consumption-day">{{ number_format($totalDailyConsumption, 2) }} kW</td>
                                    <td class="text-end fw-bold consumption-week d-none">{{ number_format($totalWeeklyConsumption, 2) }} kW</td>
                                    <td class="text-end fw-bold consumption-month d-none">{{ number_format($totalMonthlyConsumption, 2) }} kW</td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Répartition de la consommation
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="consumptionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>Conseils d'économie d'énergie
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-power-off fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Éteignez vos appareils</h5>
                                    <p class="text-muted mb-0">Désactivez les appareils inutilisés plutôt que de les laisser en veille.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Programmez vos usages</h5>
                                    <p class="text-muted mb-0">Utilisez les fonctions de programmation pour optimiser l'usage.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3 text-info">
                                    <i class="fas fa-thermometer-half fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Réglez la température</h5>
                                    <p class="text-muted mb-0">Chaque degré supplémentaire représente environ 7% de consommation en plus.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique
    const ctx = document.getElementById('consumptionChart').getContext('2d');
    
    // Données pour le graphique
    const labels = @json($objects->pluck('name'));
    const dailyData = @json($objects->pluck('daily_consumption'));
    const weeklyData = @json($objects->pluck('weekly_consumption'));
    const monthlyData = @json($objects->pluck('monthly_consumption'));
    
    // Couleurs pour le graphique
    const backgroundColors = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 205, 86, 0.7)',
        'rgba(201, 203, 207, 0.7)',
        // Répéter les couleurs si nécessaire
    ];
    
    // Créer le graphique
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Consommation quotidienne (kW)',
                data: dailyData,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    
    // Gestion des boutons pour changer la période
    document.getElementById('btn-day').addEventListener('click', function() {
        togglePeriod('day');
        updateChart(myChart, 'Consommation quotidienne (kW)', dailyData);
        this.classList.add('active');
        document.getElementById('btn-week').classList.remove('active');
        document.getElementById('btn-month').classList.remove('active');
    });
    
    document.getElementById('btn-week').addEventListener('click', function() {
        togglePeriod('week');
        updateChart(myChart, 'Consommation hebdomadaire (kW)', weeklyData);
        this.classList.add('active');
        document.getElementById('btn-day').classList.remove('active');
        document.getElementById('btn-month').classList.remove('active');
    });
    
    document.getElementById('btn-month').addEventListener('click', function() {
        togglePeriod('month');
        updateChart(myChart, 'Consommation mensuelle (kW)', monthlyData);
        this.classList.add('active');
        document.getElementById('btn-day').classList.remove('active');
        document.getElementById('btn-week').classList.remove('active');
    });
    
    // Fonction pour mettre à jour le graphique
    function updateChart(chart, label, data) {
        chart.data.datasets[0].label = label;
        chart.data.datasets[0].data = data;
        chart.update();
    }
    
    // Fonction pour basculer l'affichage des périodes
    function togglePeriod(period) {
        document.querySelectorAll('.consumption-day, .consumption-week, .consumption-month, .percent-day, .percent-week, .percent-month, .percent-day-bar, .percent-week-bar, .percent-month-bar').forEach(el => {
            el.classList.add('d-none');
        });
        
        document.querySelectorAll(`.consumption-${period}, .percent-${period}, .percent-${period}-bar`).forEach(el => {
            el.classList.remove('d-none');
        });
    }
});
</script>
@endpush
@endsection
