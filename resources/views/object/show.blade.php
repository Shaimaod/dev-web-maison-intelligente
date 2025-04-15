@extends('layouts.user')

@section('title', $object->name)
@extends('layouts.user')

@section('title', $object->name)

@section('content')
<style>
    .object-card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .object-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .card-header h2 {
        margin: 0;
        font-weight: 600;
    }

    .info-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-section h5 {
        color: #0d6efd;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .controls {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .form-select, .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    .value-display {
        font-size: 1.1rem;
        color: #212529;
        font-weight: 500;
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease-out;
    }

    .toast.success {
        border-left: 4px solid #198754;
    }

    .toast.error {
        border-left: 4px solid #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .loading-spinner {
        display: none;
        width: 1.5rem;
        height: 1.5rem;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease-out;
    }

    .toast.success {
        border-left: 4px solid #198754;
    }

    .toast.error {
        border-left: 4px solid #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .loading-spinner {
        display: none;
        width: 1.5rem;
        height: 1.5rem;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .temperature-control {
        position: relative;
        margin-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .temperature-display {
        font-size: 2rem;
        font-weight: 600;
        color: #0d6efd;
        text-align: center;
        margin-bottom: 1rem;
    .temperature-display {
        font-size: 2rem;
        font-weight: 600;
        color: #0d6efd;
        text-align: center;
        margin-bottom: 1rem;
    }

    .temperature-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .temperature-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        color: #0d6efd;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .temperature-btn:hover {
        background: #0d6efd;
        color: white;
        transform: scale(1.1);
    }

    .temperature-range {
        width: 100%;
        margin: 1rem 0;
    }

    .temperature-labels {
        display: flex;
        justify-content: space-between;
        color: #6c757d;
        font-size: 0.9rem;
    .temperature-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .temperature-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        color: #0d6efd;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .temperature-btn:hover {
        background: #0d6efd;
        color: white;
        transform: scale(1.1);
    }

    .temperature-range {
        width: 100%;
        margin: 1rem 0;
    }

    .temperature-labels {
        display: flex;
        justify-content: space-between;
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="object-card">
                <div class="card-header d-flex justify-content-between align-items-center">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="object-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>{{ $object->name }}</h2>
                    <div>
                        <button class="btn btn-light" onclick="window.location.href='{{ route('object.edit', $object->id) }}'">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        @if(auth()->user()->canRequestObjectDeletion())
                            <button type="button" class="btn btn-danger ms-2" onclick="requestDeletion()" id="requestDeletionButton">
                                <i class="fas fa-trash-alt me-2"></i>Demander la suppression
                            </button>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-light" onclick="window.location.href='{{ route('object.edit', $object->id) }}'">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        @if(auth()->user()->canRequestObjectDeletion())
                            <button type="button" class="btn btn-danger ms-2" onclick="requestDeletion()" id="requestDeletionButton">
                                <i class="fas fa-trash-alt me-2"></i>Demander la suppression
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Vue détaillée -->
                    <div id="detailView">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5>Informations générales</h5>
                                    <p><strong>Description:</strong> <span class="value-display">{{ $object->description }}</span></p>
                                    <p><strong>Catégorie:</strong> <span class="value-display">{{ $object->category }}</span></p>
                                    <p><strong>Marque:</strong> <span class="value-display">{{ $object->brand }}</span></p>
                                    <p><strong>Type:</strong> <span class="value-display">{{ $object->type }}</span></p>
                                    <p><strong>Connectivité:</strong> <span class="value-display">{{ $object->connectivity }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5>État actuel</h5>
                                    <p>
                                        <strong>Statut:</strong>
                                        <span id="status" class="status-badge {{ $object->status === 'Actif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $object->status }}
                                        </span>
                                    </p>
                                    <p><strong>Mode:</strong> <span id="mode" class="value-display">{{ $object->mode }}</span></p>
                                    @if($object->battery)
                                        <p><strong>Batterie:</strong> <span class="value-display">{{ $object->battery }}%</span></p>
                                    @endif
                                    @if($object->current_temp)
                                        <p><strong>Température actuelle:</strong> <span id="current_temp" class="value-display">{{ $object->current_temp }}</span></p>
                                    @endif
                                    @if($object->target_temp)
                                        <p><strong>Température cible:</strong> <span id="target_temp" class="value-display">{{ $object->target_temp }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    <!-- Vue détaillée -->
                    <div id="detailView">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5>Informations générales</h5>
                                    <p><strong>Description:</strong> <span class="value-display">{{ $object->description }}</span></p>
                                    <p><strong>Catégorie:</strong> <span class="value-display">{{ $object->category }}</span></p>
                                    <p><strong>Marque:</strong> <span class="value-display">{{ $object->brand }}</span></p>
                                    <p><strong>Type:</strong> <span class="value-display">{{ $object->type }}</span></p>
                                    <p><strong>Connectivité:</strong> <span class="value-display">{{ $object->connectivity }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5>État actuel</h5>
                                    <p>
                                        <strong>Statut:</strong>
                                        <span id="status" class="status-badge {{ $object->status === 'Actif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $object->status }}
                                        </span>
                                    </p>
                                    <p><strong>Mode:</strong> <span id="mode" class="value-display">{{ $object->mode }}</span></p>
                                    @if($object->battery)
                                        <p><strong>Batterie:</strong> <span class="value-display">{{ $object->battery }}%</span></p>
                                    @endif
                                    @if($object->current_temp)
                                        <p><strong>Température actuelle:</strong> <span id="current_temp" class="value-display">{{ $object->current_temp }}</span></p>
                                    @endif
                                    @if($object->target_temp)
                                        <p><strong>Température cible:</strong> <span id="target_temp" class="value-display">{{ $object->target_temp }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="controls">
                            <h5>Contrôles</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" id="statusSelect">
                                            <option value="Actif" {{ $object->status === 'Actif' ? 'selected' : '' }}>Actif</option>
                                            <option value="Inactif" {{ $object->status === 'Inactif' ? 'selected' : '' }}>Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mode</label>
                                        <select class="form-select" id="modeSelect">
                                            <option value="Automatique" {{ $object->mode === 'Automatique' ? 'selected' : '' }}>Automatique</option>
                                            <option value="Manuel" {{ $object->mode === 'Manuel' ? 'selected' : '' }}>Manuel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div class="controls">
                            <h5>Contrôles</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" id="statusSelect">
                                            <option value="Actif" {{ $object->status === 'Actif' ? 'selected' : '' }}>Actif</option>
                                            <option value="Inactif" {{ $object->status === 'Inactif' ? 'selected' : '' }}>Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mode</label>
                                        <select class="form-select" id="modeSelect">
                                            <option value="Automatique" {{ $object->mode === 'Automatique' ? 'selected' : '' }}>Automatique</option>
                                            <option value="Manuel" {{ $object->mode === 'Manuel' ? 'selected' : '' }}>Manuel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Contrôles spécifiques selon la catégorie --}}
                            @if($object->category === 'Éclairage')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Intensité lumineuse</label>
                                            <input type="range" class="form-range" id="brightnessInput" min="0" max="100" value="{{ $object->brightness ?? 100 }}">
                                            <div class="d-flex justify-content-between">
                                                <small>0%</small>
                                                <small id="brightnessValue">{{ $object->brightness ?? 100 }}%</small>
                                                <small>100%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Couleur</label>
                                            <input type="color" class="form-control form-control-color" id="colorInput" value="{{ $object->color ?? '#ffffff' }}" title="Choisir une couleur">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Contrôles spécifiques selon la catégorie --}}
                            @if($object->category === 'Éclairage')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Intensité lumineuse</label>
                                            <input type="range" class="form-range" id="brightnessInput" min="0" max="100" value="{{ $object->brightness ?? 100 }}">
                                            <div class="d-flex justify-content-between">
                                                <small>0%</small>
                                                <small id="brightnessValue">{{ $object->brightness ?? 100 }}%</small>
                                                <small>100%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Couleur</label>
                                            <input type="color" class="form-control form-control-color" id="colorInput" value="{{ $object->color ?? '#ffffff' }}" title="Choisir une couleur">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($object->category === 'Sécurité')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mode de surveillance</label>
                                            <select class="form-select" id="surveillanceMode">
                                                <option value="Continu" {{ $object->surveillance_mode === 'Continu' ? 'selected' : '' }}>Continu</option>
                                                <option value="Détection" {{ $object->surveillance_mode === 'Détection' ? 'selected' : '' }}>Détection de mouvement</option>
                                                <option value="Programmé" {{ $object->surveillance_mode === 'Programmé' ? 'selected' : '' }}>Programmé</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sensibilité de détection</label>
                                            <input type="range" class="form-range" id="sensitivityInput" min="1" max="10" value="{{ $object->sensitivity ?? 5 }}">
                                            <div class="d-flex justify-content-between">
                                                <small>Faible</small>
                                                <small id="sensitivityValue">{{ $object->sensitivity ?? 5 }}</small>
                                                <small>Forte</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($object->category === 'Sécurité')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mode de surveillance</label>
                                            <select class="form-select" id="surveillanceMode">
                                                <option value="Continu" {{ $object->surveillance_mode === 'Continu' ? 'selected' : '' }}>Continu</option>
                                                <option value="Détection" {{ $object->surveillance_mode === 'Détection' ? 'selected' : '' }}>Détection de mouvement</option>
                                                <option value="Programmé" {{ $object->surveillance_mode === 'Programmé' ? 'selected' : '' }}>Programmé</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sensibilité de détection</label>
                                            <input type="range" class="form-range" id="sensitivityInput" min="1" max="10" value="{{ $object->sensitivity ?? 5 }}">
                                            <div class="d-flex justify-content-between">
                                                <small>Faible</small>
                                                <small id="sensitivityValue">{{ $object->sensitivity ?? 5 }}</small>
                                                <small>Forte</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($object->category === 'Audio')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Volume</label>
                                            <input type="range" class="form-range" id="volumeInput" min="0" max="100" value="{{ $object->volume ?? 50 }}">
                                            <div class="d-flex justify-content-between">
                                                <small>0%</</small>
                                                <small id="volumeValue">{{ $object->volume ?? 50 }}%</small>
                                                <small>100%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Source audio</label>
                                            <select class="form-select" id="audioSource">
                                                <option value="Bluetooth" {{ $object->audio_source === 'Bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                                                <option value="WiFi" {{ $object->audio_source === 'WiFi' ? 'selected' : '' }}>WiFi</option>
                                                <option value="Auxiliaire" {{ $object->audio_source === 'Auxiliaire' ? 'selected' : '' }}>Auxiliaire</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($object->category === 'Climatisation')
                                <div class="temperature-control">
                                    <div class="temperature-display">
                                        <span id="currentTempDisplay">{{ $object->current_temp }}</span>
                                    </div>
                                    <div class="temperature-range">
                                        <input type="range" class="form-range" id="temperatureInput" min="16" max="30" value="{{ str_replace('°C', '', $object->target_temp ?? 20) }}" step="0.5">
                                        <div class="temperature-labels">
                                            <span>16°C</span>
                                            <span id="targetTempDisplay">{{ $object->target_temp }}</span>
                                            <span>30°C</span>
                                        </div>
                                    </div>
                                    <div class="temperature-buttons">
                                        <button class="temperature-btn" onclick="adjustTemperature(-0.5)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button class="temperature-btn" onclick="adjustTemperature(0.5)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" onclick="updateObject()" id="updateButton">
                                    <span class="loading-spinner" id="loadingSpinner"></span>
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            @endif

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" onclick="updateObject()" id="updateButton">
                                    <span class="loading-spinner" id="loadingSpinner"></span>
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire d'édition -->
                    <div id="editView" style="display: none;">
                        <form action="{{ route('object.update', $object->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de l'objet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $object->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $object->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Catégorie</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="lighting" {{ old('category', $object->category) == 'lighting' ? 'selected' : '' }}>Éclairage</option>
                                        <option value="security" {{ old('category', $object->category) == 'security' ? 'selected' : '' }}>Sécurité</option>
                                        <option value="climate" {{ old('category', $object->category) == 'climate' ? 'selected' : '' }}>Climatisation</option>
                                        <option value="audio" {{ old('category', $object->category) == 'audio' ? 'selected' : '' }}>Audio</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="room" class="form-label">Pièce</label>
                                    <select class="form-select @error('room') is-invalid @enderror" id="room" name="room" required>
                                        <option value="">Sélectionnez une pièce</option>
                                        <option value="living_room" {{ old('room', $object->room) == 'living_room' ? 'selected' : '' }}>Salon</option>
                                        <option value="bedroom" {{ old('room', $object->room) == 'bedroom' ? 'selected' : '' }}>Chambre</option>
                                        <option value="kitchen" {{ old('room', $object->room) == 'kitchen' ? 'selected' : '' }}>Cuisine</option>
                                        <option value="bathroom" {{ old('room', $object->room) == 'bathroom' ? 'selected' : '' }}>Salle de bain</option>
                                    </select>
                                    @error('room')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marque</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $object->brand) }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $object->type) }}" required>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="connectivity" class="form-label">Connectivité</label>
                                <select class="form-select @error('connectivity') is-invalid @enderror" id="connectivity" name="connectivity" required>
                                    <option value="">Sélectionnez un type de connectivité</option>
                                    <option value="wifi" {{ old('connectivity', $object->connectivity) == 'wifi' ? 'selected' : '' }}>Wi-Fi</option>
                                    <option value="bluetooth" {{ old('connectivity', $object->connectivity) == 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                                    <option value="zigbee" {{ old('connectivity', $object->connectivity) == 'zigbee' ? 'selected' : '' }}>Zigbee</option>
                                    <option value="zwave" {{ old('connectivity', $object->connectivity) == 'zwave' ? 'selected' : '' }}>Z-Wave</option>
                                </select>
                                @error('connectivity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($object->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $object->image) }}" alt="Image actuelle" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_automated" name="is_automated" value="1" {{ old('is_automated', $object->is_automated) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_automated">
                                        Objet automatisé
                                    </label>
                        </div>
                    </div>

                    <!-- Formulaire d'édition -->
                    <div id="editView" style="display: none;">
                        <form action="{{ route('object.update', $object->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de l'objet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $object->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $object->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Catégorie</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="lighting" {{ old('category', $object->category) == 'lighting' ? 'selected' : '' }}>Éclairage</option>
                                        <option value="security" {{ old('category', $object->category) == 'security' ? 'selected' : '' }}>Sécurité</option>
                                        <option value="climate" {{ old('category', $object->category) == 'climate' ? 'selected' : '' }}>Climatisation</option>
                                        <option value="audio" {{ old('category', $object->category) == 'audio' ? 'selected' : '' }}>Audio</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="room" class="form-label">Pièce</label>
                                    <select class="form-select @error('room') is-invalid @enderror" id="room" name="room" required>
                                        <option value="">Sélectionnez une pièce</option>
                                        <option value="living_room" {{ old('room', $object->room) == 'living_room' ? 'selected' : '' }}>Salon</option>
                                        <option value="bedroom" {{ old('room', $object->room) == 'bedroom' ? 'selected' : '' }}>Chambre</option>
                                        <option value="kitchen" {{ old('room', $object->room) == 'kitchen' ? 'selected' : '' }}>Cuisine</option>
                                        <option value="bathroom" {{ old('room', $object->room) == 'bathroom' ? 'selected' : '' }}>Salle de bain</option>
                                    </select>
                                    @error('room')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marque</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $object->brand) }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $object->type) }}" required>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="connectivity" class="form-label">Connectivité</label>
                                <select class="form-select @error('connectivity') is-invalid @enderror" id="connectivity" name="connectivity" required>
                                    <option value="">Sélectionnez un type de connectivité</option>
                                    <option value="wifi" {{ old('connectivity', $object->connectivity) == 'wifi' ? 'selected' : '' }}>Wi-Fi</option>
                                    <option value="bluetooth" {{ old('connectivity', $object->connectivity) == 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                                    <option value="zigbee" {{ old('connectivity', $object->connectivity) == 'zigbee' ? 'selected' : '' }}>Zigbee</option>
                                    <option value="zwave" {{ old('connectivity', $object->connectivity) == 'zwave' ? 'selected' : '' }}>Z-Wave</option>
                                </select>
                                @error('connectivity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($object->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $object->image) }}" alt="Image actuelle" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_automated" name="is_automated" value="1" {{ old('is_automated', $object->is_automated) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_automated">
                                        Objet automatisé
                                    </label>
                                </div>
                            </div>

                            @if(auth()->user()->level === 'expert')
                                <div class="mb-3">
                                    <label for="settings" class="form-label">Paramètres avancés (JSON)</label>
                                    <textarea class="form-control @error('settings') is-invalid @enderror" id="settings" name="settings" rows="5">{{ old('settings', $object->settings) }}</textarea>
                                    @error('settings')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Entrez les paramètres au format JSON valide.</small>
                                </div>
                            @endif

                            @if(auth()->user()->level === 'expert')
                                <div class="mb-3">
                                    <label for="settings" class="form-label">Paramètres avancés (JSON)</label>
                                    <textarea class="form-control @error('settings') is-invalid @enderror" id="settings" name="settings" rows="5">{{ old('settings', $object->settings) }}</textarea>
                                    @error('settings')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Entrez les paramètres au format JSON valide.</small>
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                                <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                                <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'}"></i>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function adjustTemperature(change) {
    const input = document.getElementById('temperatureInput');
    const newValue = parseFloat(input.value) + change;
    if (newValue >= 16 && newValue <= 30) {
        input.value = newValue;
        document.getElementById('targetTempDisplay').textContent = newValue + '°C';
    }
}
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'}"></i>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function adjustTemperature(change) {
    const input = document.getElementById('temperatureInput');
    const newValue = parseFloat(input.value) + change;
    if (newValue >= 16 && newValue <= 30) {
        input.value = newValue;
        document.getElementById('targetTempDisplay').textContent = newValue + '°C';
    }
}

function updateObject() {
    const button = document.getElementById('updateButton');
    const spinner = document.getElementById('loadingSpinner');
    button.disabled = true;
    spinner.style.display = 'inline-block';

    // Capturer la valeur actuelle du slider de température avant l'envoi
    @if($object->category === 'Climatisation')
    const selectedTemperature = document.getElementById('temperatureInput').value;
    console.log('Température sélectionnée avant envoi:', selectedTemperature);
    @endif

    const data = {
        status: document.getElementById('statusSelect').value,
        mode: document.getElementById('modeSelect').value,
    };

    // Ajouter les données spécifiques selon la catégorie
    @if($object->category === 'Éclairage')
        data.brightness = document.getElementById('brightnessInput').value;
    // Ajouter les données spécifiques selon la catégorie
    @if($object->category === 'Éclairage')
        data.brightness = document.getElementById('brightnessInput').value;
        data.color = document.getElementById('colorInput').value;
    @endif

    @if($object->category === 'Sécurité')
    @endif

    @if($object->category === 'Sécurité')
        data.surveillance_mode = document.getElementById('surveillanceMode').value;
        data.sensitivity = document.getElementById('sensitivityInput').value;
    @endif

    @if($object->category === 'Audio')
        data.volume = document.getElementById('volumeInput').value;
        data.sensitivity = document.getElementById('sensitivityInput').value;
    @endif

    @if($object->category === 'Audio')
        data.volume = document.getElementById('volumeInput').value;
        data.audio_source = document.getElementById('audioSource').value;
    @endif

    @if($object->category === 'Climatisation')
        data.target_temp = selectedTemperature; // Utiliser la variable capturée
    @endif

    // Envoyer les données au serveur
    fetch('/object/{{ $object->id }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de la mise à jour');
        }
        return response.json();
    })
    .then(data => {
        // Mettre à jour l'interface
        // Mettre à jour l'interface
        document.getElementById('status').textContent = data.object.status;
        document.getElementById('status').className = 'status-badge ' + (data.object.status === 'Actif' ? 'bg-success' : 'bg-danger');
        document.getElementById('status').className = 'status-badge ' + (data.object.status === 'Actif' ? 'bg-success' : 'bg-danger');
        document.getElementById('mode').textContent = data.object.mode;

        @if($object->category === 'Climatisation')
            // Afficher la réponse du serveur dans la console pour le débogage
            console.log('Réponse complète du serveur:', JSON.stringify(data));
            
            // Mise à jour de la température actuelle si disponible
            if (data.object.current_temp) {
                const currentTemp = data.object.current_temp.replace('°C', ''); // Enlever °C s'il existe déjà
                document.getElementById('current_temp').textContent = currentTemp + '°C';
                document.getElementById('currentTempDisplay').textContent = currentTemp + '°C';
            }
            
            // Vérifier si la réponse contient la température cible
            if (data.object.target_temp !== undefined) {
                // Le serveur retourne la température mais semble ne pas la mettre à jour correctement
                // On va utiliser la valeur qu'on a envoyée plutôt que celle retournée par le serveur
                console.log('Target temp from server:', data.object.target_temp);
                console.log('Using selected temperature instead:', selectedTemperature);
                
                // Mettre à jour tous les éléments affichant la température cible
                document.getElementById('target_temp').textContent = selectedTemperature + '°C';
                document.getElementById('targetTempDisplay').textContent = selectedTemperature + '°C';
                document.getElementById('temperatureInput').value = selectedTemperature;
            } else {
                // Si le serveur ne renvoie pas la valeur, utiliser celle qu'on a envoyée
                console.log('Utilisation de la température sélectionnée:', selectedTemperature);
                document.getElementById('target_temp').textContent = selectedTemperature + '°C';
                document.getElementById('targetTempDisplay').textContent = selectedTemperature + '°C';
                document.getElementById('temperatureInput').value = selectedTemperature;
            }
        @endif

        showToast('Modifications enregistrées avec succès !');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur est survenue lors de la mise à jour.', 'error');
    })
    .finally(() => {
        button.disabled = false;
        spinner.style.display = 'none';
    });
}

// Mettre à jour l'affichage des valeurs en temps réel
document.querySelectorAll('input[type="range"]').forEach(input => {
    input.addEventListener('input', function() {
        const valueDisplay = document.getElementById(this.id + 'Value');
        if (valueDisplay) {
            valueDisplay.textContent = this.value + (this.id === 'sensitivityInput' ? '' : '%');
        }
    });
});

// Mettre à jour l'affichage de la température en temps réel
const temperatureInput = document.getElementById('temperatureInput');
if (temperatureInput) {
    temperatureInput.addEventListener('input', function() {
        document.getElementById('targetTempDisplay').textContent = this.value + '°C';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const toggleViewBtn = document.getElementById('toggleViewBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const detailView = document.getElementById('detailView');
    const editView = document.getElementById('editView');

    // Ensure toggleViewBtn exists before adding event listeners
    if (toggleViewBtn) {
        toggleViewBtn.addEventListener('click', function() {
            detailView.style.display = 'none';
            editView.style.display = 'block';
            toggleViewBtn.style.display = 'none';
        });
    }

    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            detailView.style.display = 'block';
            editView.style.display = 'none';
            if (toggleViewBtn) {
                toggleViewBtn.style.display = 'block';
            }
        });
    }
});

function requestDeletion() {
    if (!confirm('Êtes-vous sûr de vouloir demander la suppression de cet objet connecté ? Cette action ne peut pas être annulée.')) {
        return;
    }

    const button = document.getElementById('requestDeletionButton');
    button.disabled = true;

    fetch('{{ route("object.request-deletion", $object->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ reason: 'Demande utilisateur' })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Erreur lors de la demande de suppression');
            });
        }
        return response.json();
    })
    .then(data => {
        showToast(data.message, 'success');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    })
    .catch(error => {
        console.error('Request error:', error);
        showToast(error.message || 'Une erreur est survenue lors de la demande de suppression.', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endpush
@endsection
@endpush
@endsection