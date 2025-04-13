@extends('layouts.user')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Modifier l'objet connecté</h5>
                </div>
                <div class="card-body">
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="{{ route('connected.objects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 