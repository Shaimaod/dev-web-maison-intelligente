@extends('layouts.user')

@section('title', 'Modifier ' . $object->name)

@section('content')
<style>
    .form-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h1 {
        color: #0d6efd;
    }

    .form-container .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-container .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .form-container .form-label {
        font-weight: bold;
    }

    .form-container .form-control {
        border-radius: 5px;
    }

    .form-container .invalid-feedback {
        font-size: 0.875rem;
    }
</style>

<div class="container py-4">
    <div class="form-container">
        <h1 class="h3 mb-4">Modifier l'objet : {{ $object->name }}</h1>
        <form action="{{ route('object.updateForEdit', $object->id) }}" method="POST" enctype="multipart/form-data">
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
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $object->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="Éclairage" {{ old('category', $object->category) == 'Éclairage' ? 'selected' : '' }}>Éclairage</option>
                        <option value="Climatisation" {{ old('category', $object->category) == 'Climatisation' ? 'selected' : '' }}>Climatisation</option>
                        <option value="Sécurité" {{ old('category', $object->category) == 'Sécurité' ? 'selected' : '' }}>Sécurité</option>
                        <option value="Électroménager" {{ old('category', $object->category) == 'Électroménager' ? 'selected' : '' }}>Électroménager</option>
                        <option value="Audio" {{ old('category', $object->category) == 'Audio' ? 'selected' : '' }}>Audio</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="room" class="form-label">Pièce</label>
                    <select class="form-control @error('room') is-invalid @enderror" id="room" name="room" required>
                        <option value="Salon" {{ old('room', $object->room) == 'Salon' ? 'selected' : '' }}>Salon</option>
                        <option value="Cuisine" {{ old('room', $object->room) == 'Cuisine' ? 'selected' : '' }}>Cuisine</option>
                        <option value="Chambre" {{ old('room', $object->room) == 'Chambre' ? 'selected' : '' }}>Chambre</option>
                        <option value="Salle de bain" {{ old('room', $object->room) == 'Salle de bain' ? 'selected' : '' }}>Salle de bain</option>
                        <option value="Entrée" {{ old('room', $object->room) == 'Entrée' ? 'selected' : '' }}>Entrée</option>
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
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="Ampoule" {{ old('type', $object->type) == 'Ampoule' ? 'selected' : '' }}>Ampoule</option>
                        <option value="Thermostat" {{ old('type', $object->type) == 'Thermostat' ? 'selected' : '' }}>Thermostat</option>
                        <option value="Caméra" {{ old('type', $object->type) == 'Caméra' ? 'selected' : '' }}>Caméra</option>
                        <option value="Prise" {{ old('type', $object->type) == 'Prise' ? 'selected' : '' }}>Prise</option>
                        <option value="Détecteur" {{ old('type', $object->type) == 'Détecteur' ? 'selected' : '' }}>Détecteur</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="Actif" {{ old('status', $object->status) == 'Actif' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactif" {{ old('status', $object->status) == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="connectivity" class="form-label">Connectivité</label>
                    <select class="form-control @error('connectivity') is-invalid @enderror" id="connectivity" name="connectivity" required>
                        <option value="Wi-Fi" {{ old('connectivity', $object->connectivity) == 'Wi-Fi' ? 'selected' : '' }}>Wi-Fi</option>
                        <option value="Zigbee" {{ old('connectivity', $object->connectivity) == 'Zigbee' ? 'selected' : '' }}>Zigbee</option>
                        <option value="Z-Wave" {{ old('connectivity', $object->connectivity) == 'Z-Wave' ? 'selected' : '' }}>Z-Wave</option>
                    </select>
                    @error('connectivity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="battery" class="form-label">Batterie (%)</label>
                    <input type="number" class="form-control @error('battery') is-invalid @enderror" id="battery" name="battery" value="{{ old('battery', $object->battery) }}" min="0" max="100">
                    @error('battery')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="mode" class="form-label">Mode</label>
                    <select class="form-control @error('mode') is-invalid @enderror" id="mode" name="mode">
                        <option value="Automatique" {{ old('mode', $object->mode) == 'Automatique' ? 'selected' : '' }}>Automatique</option>
                        <option value="Manuel" {{ old('mode', $object->mode) == 'Manuel' ? 'selected' : '' }}>Manuel</option>
                    </select>
                    @error('mode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_automated" name="is_automated" value="1" {{ old('is_automated', $object->is_automated) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_automated">Automatisé</label>
                </div>
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

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection