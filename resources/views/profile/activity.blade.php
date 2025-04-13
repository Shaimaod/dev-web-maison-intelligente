@extends('layouts.user')

@section('title', 'Historique des activités')

@section('content')
<style>
    .activity-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .activity-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .activity-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .activity-content {
        padding: 1.5rem;
    }

    .activity-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .activity-table th {
        background: #f8f9fa;
        padding: 1rem;
        font-weight: 600;
        text-align: left;
        color: #495057;
    }

    .activity-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .activity-table tr:hover {
        background: #f8f9fa;
    }

    .activity-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .badge-login {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-logout {
        background: #fbe9e7;
        color: #d84315;
    }

    .badge-search {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-profile {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .badge-object {
        background: #fff3e0;
        color: #f57c00;
    }

    .badge-default {
        background: #eceff1;
        color: #455a64;
    }

    .details-button {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .details-button:hover {
        background: var(--primary-color);
        color: white;
    }

    .load-more {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .load-more-button {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .load-more-button:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }

    .load-more-button:disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        transform: none;
    }

    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .details-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .details-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .details-list li:last-child {
        border-bottom: none;
    }

    .details-label {
        font-weight: 600;
        color: #495057;
        margin-right: 0.5rem;
    }

    .no-activities {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .no-activities i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #adb5bd;
    }
</style>

<div class="activity-container">
    <div class="activity-card">
        <div class="activity-header">
            <h2>
                <i class="fas fa-history me-2"></i>
                Historique des activités
                @if(Auth::id() !== $user->id)
                    - {{ $user->name }} {{ $user->surname }}
                @endif
            </h2>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('profile.show') }}" class="back-button">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="activity-content">
            @if($activities->isEmpty())
                <div class="no-activities">
                    <i class="fas fa-history"></i>
                    <h3>Aucune activité enregistrée</h3>
                    <p>Aucune action n'a été effectuée pour le moment.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @switch($activity->action_type)
                                            @case('login')
                                                <span class="activity-badge badge-login">
                                                    <i class="fas fa-sign-in-alt me-1"></i>Connexion
                                                </span>
                                                @break
                                            @case('logout')
                                                <span class="activity-badge badge-logout">
                                                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                                                </span>
                                                @break
                                            @case('search')
                                                <span class="activity-badge badge-search">
                                                    <i class="fas fa-search me-1"></i>Recherche
                                                </span>
                                                @break
                                            @case('profile_update')
                                                <span class="activity-badge badge-profile">
                                                    <i class="fas fa-user-edit me-1"></i>Profil
                                                </span>
                                                @break
                                            @case('object_add')
                                            @case('object_update')
                                            @case('object_delete')
                                                <span class="activity-badge badge-object">
                                                    <i class="fas fa-plug me-1"></i>Objet
                                                </span>
                                                @break
                                            @case('deletion_request')
                                                <span class="activity-badge badge-object">
                                                    <i class="fas fa-trash-alt me-1"></i>Demande de suppression
                                                </span>
                                                @break
                                            @case('object_deletion_requested')
                                                <span class="activity-badge badge-object">
                                                    <i class="fas fa-trash-alt me-1"></i>Demande de suppression
                                                </span>
                                                @break
                                            @default
                                                <span class="activity-badge badge-default">
                                                    <i class="fas fa-info-circle me-1"></i>{{ ucfirst($activity->action_type) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>
                                        @if($activity->details)
                                            <button type="button" class="details-button" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $activity->id }}">
                                                <i class="fas fa-info-circle me-1"></i>Voir les détails
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="detailsModal{{ $activity->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $activity->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailsModalLabel{{ $activity->id }}">
                                                                <i class="fas fa-info-circle me-2"></i>Détails de l'activité
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul class="details-list">
                                                                @foreach($activity->details as $key => $value)
                                                                    <li>
                                                                        <span class="details-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Aucun détail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="load-more">
                    <button class="load-more-button" id="loadMoreButton">
                        Charger plus d'activités
                        <span class="loading-spinner" id="loadingSpinner"></span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        const loadMoreButton = document.getElementById('loadMoreButton');
        const loadingSpinner = document.getElementById('loadingSpinner');
        let isLoading = false;
        let hasMorePages = true;

        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', loadMoreActivities);
        }

        function loadMoreActivities() {
            if (isLoading || !hasMorePages) return;

            isLoading = true;
            loadMoreButton.disabled = true;
            loadingSpinner.style.display = 'inline-block';

            currentPage++;

            fetch(`{{ route('profile.activity', ['user' => $user->id]) }}?page=${currentPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newRows = doc.querySelectorAll('.activity-table tbody tr');
                
                if (newRows.length > 0) {
                    const tbody = document.querySelector('.activity-table tbody');
                    newRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
                } else {
                    hasMorePages = false;
                    loadMoreButton.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des activités:', error);
            })
            .finally(() => {
                isLoading = false;
                loadMoreButton.disabled = false;
                loadingSpinner.style.display = 'none';
            });
        }
    });
</script>
@endpush
@endsection 