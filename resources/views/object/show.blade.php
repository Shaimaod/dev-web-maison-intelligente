@extends('layouts.app')

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

    .temperature-control {
        position: relative;
    }

    .temperature-control .form-control {
        padding-right: 2.5rem;
    }

    .temperature-control::after {
        content: "°C";
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
        }
        
        .info-section, .controls {
            padding: 1rem;
        }
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card object-card">
                <div class="card-header">
                    <h2>{{ $object->name }}</h2>
                </div>
                <div class="card-body">
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
                                <p><strong>Statut:</strong> 
                                    <span id="status" class="status-badge {{ $object->status === 'Actif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $object->status }}
                                    </span>
                                </p>
                                <p><strong>Mode:</strong> <span id="mode" class="value-display">{{ $object->mode }}</span></p>
                                @if($object->current_temp)
                                    <p><strong>Température actuelle:</strong> <span id="current_temp" class="value-display">{{ $object->current_temp }}</span></p>
                                @endif
                                @if($object->target_temp)
                                    <p><strong>Température cible:</strong> <span id="target_temp" class="value-display">{{ $object->target_temp }}</span></p>
                                @endif
                                @if($object->battery)
                                    <p><strong>Batterie:</strong> <span id="battery" class="value-display">{{ $object->battery }}</span></p>
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

                        @if($object->category === 'Audio')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Volume</label>
                                        <input type="range" class="form-range" id="volumeInput" min="0" max="100" value="{{ $object->volume ?? 50 }}">
                                        <div class="d-flex justify-content-between">
                                            <small>0%</small>
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
                                            <option value="Aux" {{ $object->audio_source === 'Aux' ? 'selected' : '' }}>Entrée auxiliaire</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($object->target_temp)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 temperature-control">
                                    <label class="form-label">Température cible</label>
                                    <input type="number" class="form-control" id="targetTempInput" value="{{ str_replace('°C', '', $object->target_temp) }}" min="10" max="30" step="0.5">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-4">
                            <button class="btn btn-primary" onclick="updateObject()">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mise à jour des valeurs en temps réel pour les sliders
document.getElementById('brightnessInput')?.addEventListener('input', function() {
    document.getElementById('brightnessValue').textContent = this.value + '%';
});

document.getElementById('sensitivityInput')?.addEventListener('input', function() {
    document.getElementById('sensitivityValue').textContent = this.value;
});

document.getElementById('volumeInput')?.addEventListener('input', function() {
    document.getElementById('volumeValue').textContent = this.value + '%';
});

function updateObject() {
    const data = {
        status: document.getElementById('statusSelect').value,
        mode: document.getElementById('modeSelect').value,
    };

    // Ajouter les contrôles spécifiques selon la catégorie
    if ('{{ $object->category }}' === 'Éclairage') {
        data.brightness = parseInt(document.getElementById('brightnessInput').value);
        data.color = document.getElementById('colorInput').value;
    } else if ('{{ $object->category }}' === 'Sécurité') {
        data.surveillance_mode = document.getElementById('surveillanceMode').value;
        data.sensitivity = parseInt(document.getElementById('sensitivityInput').value);
    } else if ('{{ $object->category }}' === 'Audio') {
        data.volume = parseInt(document.getElementById('volumeInput').value);
        data.audio_source = document.getElementById('audioSource').value;
    }

    // Ajouter la température cible si elle existe
    const targetTempInput = document.getElementById('targetTempInput');
    if (targetTempInput) {
        data.target_temp = targetTempInput.value + '°C';
    }

    // Récupérer le token CSRF du meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/object/{{ $object->id }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
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
        // Mettre à jour l'affichage
        document.getElementById('status').textContent = data.object.status;
        document.getElementById('status').className = `status-badge ${data.object.status === 'Actif' ? 'bg-success' : 'bg-danger'}`;
        document.getElementById('mode').textContent = data.object.mode;
        
        // Mettre à jour les valeurs spécifiques
        if (data.object.brightness) {
            document.getElementById('brightnessValue').textContent = data.object.brightness + '%';
            document.getElementById('brightnessInput').value = data.object.brightness;
        }
        if (data.object.volume) {
            document.getElementById('volumeValue').textContent = data.object.volume + '%';
            document.getElementById('volumeInput').value = data.object.volume;
        }
        if (data.object.target_temp) {
            document.getElementById('target_temp').textContent = data.object.target_temp;
        }
        
        // Afficher un message de succès avec un toast
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-3';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>Objet mis à jour avec succès !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Supprimer le toast après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    })
    .catch(error => {
        // Afficher un message d'erreur
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed bottom-0 end-0 m-3';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-circle me-2"></i>${error.message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Supprimer le toast après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>
@endsection 