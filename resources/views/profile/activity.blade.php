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
                        <tbody id="activity-table-body">
                            @include('profile.activity-items')
                        </tbody>
                    </table>
                </div>

                <div class="load-more">
                    <button class="load-more-button" id="loadMoreButton" data-page="{{ $currentPage + 1 }}" {{ !$hasMorePages ? 'style=display:none' : '' }}>
                        Charger plus d'activités
                        <div class="spinner-border spinner-border-sm text-light" role="status" style="display: none;" id="loadingSpinner">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreButton = document.getElementById('loadMoreButton');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const tableBody = document.getElementById('activity-table-body');
        
        let isLoading = false;
        let lastLoadedId = null;
        
        // Récupérer le dernier ID chargé pour éviter les doublons
        const activityRows = document.querySelectorAll('.activity-item');
        if (activityRows.length > 0) {
            lastLoadedId = activityRows[activityRows.length - 1].dataset.id;
        }
        
        loadMoreButton.addEventListener('click', function() {
            if (isLoading) return;
            
            isLoading = true;
            loadMoreButton.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            
            const nextPage = parseInt(loadMoreButton.dataset.page);
            
            fetch(`{{ route('profile.activity', ['user' => $user->id]) }}?page=${nextPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Check if we got new rows
                const newRows = doc.querySelectorAll('.activity-item');
                
                if (newRows.length > 0) {
                    // Append new rows to table
                    const fragment = document.createDocumentFragment();
                    let newRowsAdded = false;
                    
                    newRows.forEach(row => {
                        const rowId = row.dataset.id;
                        // Only add rows we don't already have
                        if (!document.querySelector(`.activity-item[data-id="${rowId}"]`)) {
                            fragment.appendChild(row.cloneNode(true));
                            newRowsAdded = true;
                        }
                    });
                    
                    if (newRowsAdded) {
                        tableBody.appendChild(fragment);
                        loadMoreButton.dataset.page = nextPage + 1;
                    } else {
                        // If all rows were duplicates, we're at the end
                        loadMoreButton.style.display = 'none';
                    }
                } else {
                    // No more rows to show
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
        });
    });
</script>
@endpush
@endsection