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

    .temperature-control {
        position: relative;
        margin-bottom: 1.5rem;
    }

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
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="object-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>{{ $object->name }}</h2>
                    <div>
                        <a href="{{ route('dashboard.connected') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        @can('update', $object)
                        <button id="toggleViewBtn" class="btn btn-primary ms-2" onclick="toggleView()">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </button>
                        @else
                        <button class="btn btn-secondary ms-2" disabled title="Niveau avancé ou expert requis">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </button>
                        @endcan
                        
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

                            @if(auth()->user()->can('update', $object))
                                <!-- Boutons et contrôles de modification -->
                                <button type="button" class="btn btn-primary" id="updateButton" onclick="updateObject()">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Vous avez besoin d'être au moins de niveau <strong>avancé</strong> pour modifier cet objet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Formulaire d'édition -->
                    <div id="editView" style="display: none;">
                        <form action="{{ route('object.updateForEdit', $object->id) }}" method="POST" enctype="multipart/form-data" id="editObjectForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Champ caché pour identifier la soumission du formulaire -->
                            <input type="hidden" name="form_submit" value="true">
                            
                            <!-- Continuez avec les champs du formulaire existants -->
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
                                        <option value="Éclairage" {{ old('category', $object->category) == 'Éclairage' ? 'selected' : '' }}>Éclairage</option>
                                        <option value="Sécurité" {{ old('category', $object->category) == 'Sécurité' ? 'selected' : '' }}>Sécurité</option>
                                        <option value="Climatisation" {{ old('category', $object->category) == 'Climatisation' ? 'selected' : '' }}>Climatisation</option>
                                        <option value="Audio" {{ old('category', $object->category) == 'Audio' ? 'selected' : '' }}>Audio</option>
                                        <option value="Énergie" {{ old('category', $object->category) == 'Énergie' ? 'selected' : '' }}>Énergie</option>
                                        <option value="Électroménager" {{ old('category', $object->category) == 'Électroménager' ? 'selected' : '' }}>Électroménager</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="room" class="form-label">Pièce</label>
                                    <select class="form-select @error('room') is-invalid @enderror" id="room" name="room" required>
                                        <option value="">Sélectionnez une pièce</option>
                                        <option value="Salon" {{ old('room', $object->room) == 'Salon' ? 'selected' : '' }}>Salon</option>
                                        <option value="Chambre" {{ old('room', $object->room) == 'Chambre' ? 'selected' : '' }}>Chambre</option>
                                        <option value="Cuisine" {{ old('room', $object->room) == 'Cuisine' ? 'selected' : '' }}>Cuisine</option>
                                        <option value="Salle de bain" {{ old('room', $object->room) == 'Salle de bain' ? 'selected' : '' }}>Salle de bain</option>
                                        <option value="Couloir" {{ old('room', $object->room) == 'Couloir' ? 'selected' : '' }}>Couloir</option>
                                        <option value="Bureau" {{ old('room', $object->room) == 'Bureau' ? 'selected' : '' }}>Bureau</option>
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

                            <!-- Hidden inputs for status and mode -->
                            <input type="hidden" name="status" value="{{ old('status', $object->status) }}">
                            <input type="hidden" name="mode" value="{{ old('mode', $object->mode) }}">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitEditBtn">
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

<!-- Ajout d'un script de débug pour le formulaire -->
<script>
    document.getElementById('editObjectForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Empêcher la soumission par défaut
        
        console.log('Formulaire soumis avec les données suivantes:');
        const formData = new FormData(this);
        const formDataObj = {};
        
        // Copier les valeurs des contrôles actuels dans le formulaire
        const statusValue = document.getElementById('statusSelect').value;
        const modeValue = document.getElementById('modeSelect').value;
        
        // Mettre à jour les champs cachés
        document.querySelector('input[name="status"]').value = statusValue;
        document.querySelector('input[name="mode"]').value = modeValue;
        
        for (let pair of formData.entries()) {
            formDataObj[pair[0]] = pair[1];
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Ajouter des messages d'attente pour l'utilisateur
        const submitBtn = document.getElementById('submitEditBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour en cours...';
        
        // Envoyer la requête
        fetch(this.action, {
            method: 'POST', // Le formulaire utilise POST avec _method=PUT
            body: formData
        })
        .then(response => {
            console.log('Réponse du serveur:', response);
            
            // Vérifier si la réponse est une redirection
            if (response.redirected) {
                // En cas de redirection (succès), simplement naviguer vers l'URL de redirection
                window.location.href = response.url;
                return Promise.reject('Redirection'); // On interrompt la chaîne de promesses
            }
            
            // Si ce n'est pas une redirection, on peut tenter de parser en JSON
            if (response.headers.get('content-type')?.includes('application/json')) {
                if (!response.ok) {
                    if (response.status === 422) {
                        // Erreurs de validation
                        return response.json().then(data => {
                            throw new Error(Object.values(data.errors).flat().join('\n'));
                        });
                    }
                    throw new Error('Erreur lors de la mise à jour (status: ' + response.status + ')');
                }
                return response.json();
            }
            
            // Si ce n'est pas du JSON et pas de redirection, afficher un message de succès générique
            if (response.ok) {
                showToast('Succès', 'Objet mis à jour avec succès', 'success');
                // Recharger la page après la mise à jour
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return Promise.reject('Success'); // On interrompt la chaîne de promesses
            }
            
            throw new Error('Erreur lors de la mise à jour (status: ' + response.status + ')');
        })
        .then(data => {
            // On ne devrait arriver ici que si la réponse est du JSON valide
            showToast('Succès', data.message || 'Objet mis à jour avec succès', 'success');
            console.log('Réponse complète:', data);
            
            // Recharger la page après la mise à jour
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            // Ne pas traiter les "erreurs" de redirection ou de succès
            if (error === 'Redirection' || error === 'Success') return;
            
            console.error('Erreur:', error);
            showToast('Erreur', error.message || 'Une erreur est survenue lors de la mise à jour', 'error');
        })
        .finally(() => {
            // Ne réactive le bouton que si on n'a pas été redirigé
            if (!document.hidden) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>

<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<script>
    // Fonction pour afficher des messages toast
    function showToast(title, message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'}"></i>
            <span><strong>${title}</strong> ${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Fonction pour ajuster la température
    function adjustTemperature(change) {
        const input = document.getElementById('temperatureInput');
        const newValue = parseFloat(input.value) + change;
        
        if (newValue >= 16 && newValue <= 30) {
            input.value = newValue;
            document.getElementById('targetTempDisplay').textContent = newValue + '°C';
            updateTemperature(newValue);
        }
    }

    // Fonction spécifique pour mettre à jour la température
    function updateTemperature(temperature) {
        console.log('Envoi de la température:', temperature);
        
        const tempData = {
            target_temp: temperature
        };
        
        fetch(`/object/{{ $object->id }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(tempData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la mise à jour de la température');
            }
            return response.json();
        })
        .then(data => {
            showToast('Succès', 'Température mise à jour avec succès', 'success');
            console.log('Réponse mise à jour température:', data);
            
            // Mettre à jour l'interface
            const targetTempElement = document.getElementById('target_temp');
            if (targetTempElement) targetTempElement.textContent = temperature + '°C';
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Échec de la mise à jour de la température', 'error');
        });
    }

    @if(auth()->user()->can('update', $object))
    // Fonction pour mettre à jour un objet connecté
    function updateObject() {
        // Désactiver le bouton pendant la requête
        const button = document.getElementById('updateButton');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';

        // Récupérer la valeur actuelle du slider de température
        @if($object->category === 'Climatisation')
        const targetTemp = document.getElementById('temperatureInput').value;
        console.log('Température à envoyer:', targetTemp);
        @endif

        // Préparer les données à envoyer
        const data = {
            status: document.getElementById('statusSelect').value,
            mode: document.getElementById('modeSelect').value,
            form_submit: 'ajax' // Ajout d'un indicateur pour différencier les requêtes AJAX des soumissions de formulaire
        };

        console.log('Données avant envoi:', data);

        // Ajouter les données spécifiques selon la catégorie
        @if($object->category === 'Éclairage')
            data.brightness = document.getElementById('brightnessInput').value;
            data.color = document.getElementById('colorInput').value;
        @endif

        @if($object->category === 'Sécurité')
            data.surveillance_mode = document.getElementById('surveillanceMode').value;
            data.sensitivity = document.getElementById('sensitivityInput').value;
        @endif

        @if($object->category === 'Audio')
            data.volume = document.getElementById('volumeInput').value;
            data.audio_source = document.getElementById('audioSource').value;
        @endif

        @if($object->category === 'Climatisation')
            // S'assurer que la température est bien formatée
            data.target_temp = targetTemp + '°C';
        @endif

        // Envoyer la requête AJAX avec le bon format de données
        fetch(`/object/{{ $object->id }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la mise à jour');
            }
            return response.json();
        })
        .then(responseData => {
            // Afficher un message de succès
            showToast('Succès', responseData.message, 'success');
            // Debug - afficher la réponse complète dans la console
            console.log('Réponse du serveur:', responseData);
            // Mettre à jour l'interface utilisateur
            updateUIAfterChange(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Une erreur est survenue lors de la mise à jour.', 'error');
        })
        .finally(() => {
            // Réactiver le bouton
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }

    // Fonction pour mettre à jour l'interface utilisateur après un changement
    function updateUIAfterChange(data) {
        console.log('Mise à jour de l\'interface avec:', data);

        // Mettre à jour le statut s'il a été modifié
        if (data.status) {
            const statusElement = document.getElementById('status');
            if (statusElement) statusElement.textContent = data.status;

            // Mettre à jour la classe du badge de statut
            const statusBadge = document.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.classList.remove('bg-success', 'bg-danger');
                statusBadge.classList.add(data.status === 'Actif' ? 'bg-success' : 'bg-danger');
            }
        }

        // Mettre à jour le mode s'il a été modifié
        if (data.mode) {
            const modeElement = document.getElementById('mode');
            if (modeElement) {
                modeElement.textContent = data.mode;
                console.log('Mode mis à jour dans l\'interface:', data.mode);
            }
        }

        // Mise à jour des autres éléments spécifiques à la catégorie
        if (data.brightness) {
            const brightnessElement = document.getElementById('brightness');
            if (brightnessElement) brightnessElement.textContent = data.brightness + '%';
        }

        if (data.color) {
            const colorElement = document.getElementById('color');
            if (colorElement) {
                colorElement.textContent = data.color;
                const colorPreview = document.getElementById('colorPreview');
                if (colorPreview) colorPreview.style.backgroundColor = data.color;
            }
        }

        if (data.surveillance_mode) {
            const surveillanceModeElement = document.getElementById('surveillance_mode');
            if (surveillanceModeElement) surveillanceModeElement.textContent = data.surveillance_mode;
        }

        if (data.sensitivity) {
            const sensitivityElement = document.getElementById('sensitivity');
            if (sensitivityElement) sensitivityElement.textContent = data.sensitivity;
        }

        if (data.volume) {
            const volumeElement = document.getElementById('volume');
            if (volumeElement) volumeElement.textContent = data.volume + '%';
        }

        if (data.audio_source) {
            const audioSourceElement = document.getElementById('audio_source');
            if (audioSourceElement) audioSourceElement.textContent = data.audio_source;
        }

        if (data.target_temp) {
            const targetTempElement = document.getElementById('target_temp');
            if (targetTempElement) targetTempElement.textContent = data.target_temp;
            // Mettre également à jour l'affichage du slider
            const targetTempDisplayElement = document.getElementById('targetTempDisplay');
            if (targetTempDisplayElement) targetTempDisplayElement.textContent = data.target_temp + '°C';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Pour les changements de mode
        const modeSelect = document.getElementById('modeSelect');
        if (modeSelect) {
            modeSelect.addEventListener('change', function() {
                console.log('Mode sélectionné:', this.value);

                // Pour tester immédiatement le changement de mode
                const updateImmediately = true; // Changé pour mettre à jour immédiatement
                if (updateImmediately) {
                    const modeData = {
                        mode: this.value
                    };

                    // Envoyer uniquement la mise à jour du mode
                    fetch(`/object/{{ $object->id }}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(modeData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        showToast('Succès', 'Mode mis à jour avec succès', 'success');
                        console.log('Réponse de mise à jour du mode:', data);

                        // Mettre à jour l'affichage du mode
                        const modeElement = document.getElementById('mode');
                        if (modeElement) modeElement.textContent = modeData.mode;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour du mode:', error);
                        showToast('Erreur', 'Échec de la mise à jour du mode', 'error');
                    });
                }
            });
        }

        // Pour les changements de température
        const temperatureInput = document.getElementById('temperatureInput');
        if (temperatureInput) {
            temperatureInput.addEventListener('input', function() {
                document.getElementById('targetTempDisplay').textContent = this.value + '°C';
            });

            // Ajouter un écouteur pour le relâchement de la souris (fin d'ajustement)
            temperatureInput.addEventListener('change', function() {
                updateTemperature(this.value);
            });
        }

        // Pour le basculement entre vue détaillée et vue d'édition
        const toggleViewBtn = document.getElementById('toggleViewBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const detailView = document.getElementById('detailView');
        const editView = document.getElementById('editView');

        if (toggleViewBtn) {
            toggleViewBtn.addEventListener('click', function() {
                detailView.style.display = 'none';
                editView.style.display = 'block';
                this.style.display = 'none';
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

        // Ajouter une validation au formulaire d'édition
        const editForm = document.getElementById('editObjectForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Empêcher la soumission normale
                
                // Créer un FormData à partir du formulaire
                const formData = new FormData(this);
                
                // Convertir FormData en objet pour l'affichage de débogage
                const formDataObj = {};
                for (let [key, value] of formData.entries()) {
                    formDataObj[key] = value;
                }
                console.log('Données du formulaire:', formDataObj);
                
                // Effectuer la requête fetch avec FormData
                fetch(this.action, {
                    method: 'POST', // Le formulaire utilise POST avec _method=PUT
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la mise à jour');
                    }
                    return response.json();
                })
                .then(data => {
                    showToast('Succès', 'Objet mis à jour avec succès', 'success');
                    console.log('Réponse:', data);
                    
                    // Recharger la page après la mise à jour
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Erreur', 'Une erreur est survenue lors de la mise à jour', 'error');
                });
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
    @endif

    @if(!auth()->user()->can('update', $object))
    document.addEventListener('DOMContentLoaded', function() {
        // Désactiver tous les contrôles de formulaire
        const formControls = document.querySelectorAll('input, select, button[type="button"]:not(.btn-back)');
        formControls.forEach(control => {
            control.disabled = true;
        });
    });
    @endif

    // Ajouter l'initialisation des tooltips pour l'info-bulle sur le bouton désactivé
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Fonction utilisée pour basculer entre la vue détaillée et la vue d'édition
    function toggleView() {
        const detailView = document.getElementById('detailView');
        const editView = document.getElementById('editView');
        const toggleViewBtn = document.getElementById('toggleViewBtn');
        
        if (detailView && editView) {
            detailView.style.display = 'none';
            editView.style.display = 'block';
            if (toggleViewBtn) {
                toggleViewBtn.style.display = 'none';
            }
        }
    }
</script>
@endpush

<div id="loading-spinner" class="loading-spinner" style="display: none;"></div>

@endsection