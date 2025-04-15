@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Ajouter un nouvel objet connecté</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('connected.objects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom de l'objet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="Éclairage" {{ old('category') == 'Éclairage' ? 'selected' : '' }}>Éclairage</option>
                                    <option value="Climatisation" {{ old('category') == 'Climatisation' ? 'selected' : '' }}>Climatisation</option>
                                    <option value="Sécurité" {{ old('category') == 'Sécurité' ? 'selected' : '' }}>Sécurité</option>
                                    <option value="Électroménager" {{ old('category') == 'Électroménager' ? 'selected' : '' }}>Électroménager</option>
                                    <option value="Audio" {{ old('category') == 'Audio' ? 'selected' : '' }}>Audio</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="room" class="form-label">Pièce</label>
                                <select class="form-select @error('room') is-invalid @enderror" 
                                       id="room" name="room" required>
                                    <option value="">Sélectionnez une pièce</option>
                                    <option value="Salon" {{ old('room') == 'Salon' ? 'selected' : '' }}>Salon</option>
                                    <option value="Cuisine" {{ old('room') == 'Cuisine' ? 'selected' : '' }}>Cuisine</option>
                                    <option value="Salle à manger" {{ old('room') == 'Salle à manger' ? 'selected' : '' }}>Salle à manger</option>
                                    <option value="Chambre" {{ old('room') == 'Chambre' ? 'selected' : '' }}>Chambre</option>
                                    <option value="Bureau" {{ old('room') == 'Bureau' ? 'selected' : '' }}>Bureau</option>
                                    <option value="Salle de bain" {{ old('room') == 'Salle de bain' ? 'selected' : '' }}>Salle de bain</option>
                                    <option value="Entrée" {{ old('room') == 'Entrée' ? 'selected' : '' }}>Entrée</option>
                                    <option value="Couloir" {{ old('room') == 'Couloir' ? 'selected' : '' }}>Couloir</option>
                                    <option value="Garage" {{ old('room') == 'Garage' ? 'selected' : '' }}>Garage</option>
                                    <option value="Jardin" {{ old('room') == 'Jardin' ? 'selected' : '' }}>Jardin</option>
                                </select>
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Marque</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                       id="brand" name="brand" value="{{ old('brand') }}" required>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                       id="type" name="type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="Ampoule" {{ old('type') == 'Ampoule' ? 'selected' : '' }}>Ampoule</option>
                                    <option value="Interrupteur" {{ old('type') == 'Interrupteur' ? 'selected' : '' }}>Interrupteur</option>
                                    <option value="Thermostat" {{ old('type') == 'Thermostat' ? 'selected' : '' }}>Thermostat</option>
                                    <option value="Caméra" {{ old('type') == 'Caméra' ? 'selected' : '' }}>Caméra</option>
                                    <option value="Détecteur" {{ old('type') == 'Détecteur' ? 'selected' : '' }}>Détecteur</option>
                                    <option value="Serrure" {{ old('type') == 'Serrure' ? 'selected' : '' }}>Serrure</option>
                                    <option value="Prise" {{ old('type') == 'Prise' ? 'selected' : '' }}>Prise</option>
                                    <option value="Enceinte" {{ old('type') == 'Enceinte' ? 'selected' : '' }}>Enceinte</option>
                                    <option value="Machine à laver" {{ old('type') == 'Machine à laver' ? 'selected' : '' }}>Machine à laver</option>
                                    <option value="Lave-vaisselle" {{ old('type') == 'Lave-vaisselle' ? 'selected' : '' }}>Lave-vaisselle</option>
                                    <option value="Réfrigérateur" {{ old('type') == 'Réfrigérateur' ? 'selected' : '' }}>Réfrigérateur</option>
                                    <option value="Four" {{ old('type') == 'Four' ? 'selected' : '' }}>Four</option>
                                    <option value="Aspirateur" {{ old('type') == 'Aspirateur' ? 'selected' : '' }}>Aspirateur</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="connectivity" class="form-label">Connectivité</label>
                                <select class="form-select @error('connectivity') is-invalid @enderror" 
                                        id="connectivity" name="connectivity" required>
                                    <option value="">Sélectionnez un type de connexion</option>
                                    <option value="Wi-Fi" {{ old('connectivity') == 'Wi-Fi' ? 'selected' : '' }}>Wi-Fi</option>
                                    <option value="Bluetooth" {{ old('connectivity') == 'Bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                                    <option value="Zigbee" {{ old('connectivity') == 'Zigbee' ? 'selected' : '' }}>Zigbee</option>
                                </select>
                                @error('connectivity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image de l'objet</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mode" class="form-label">Mode</label>
                                <select class="form-select @error('mode') is-invalid @enderror" 
                                        id="mode" name="mode" required>
                                    <option value="Manuel" {{ old('mode') == 'Manuel' ? 'selected' : '' }}>Manuel</option>
                                    <option value="Automatique" {{ old('mode') == 'Automatique' ? 'selected' : '' }}>Automatique</option>
                                </select>
                                @error('mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Actif" {{ old('status') == 'Actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="Inactif" {{ old('status') == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_automated" 
                                   name="is_automated" value="1" {{ old('is_automated') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_automated">
                                Activer l'automatisation
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter l'objet
                            </button>
                            <a href="{{ route('connected.objects') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
}
</style> 