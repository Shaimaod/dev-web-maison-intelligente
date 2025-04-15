@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Ajouter un nouvel objet connecté</h2>
                </div>

                <!-- Ajout d'une div pour afficher les logs -->
                <div id="debug-logs" class="p-3 bg-light border-bottom" style="max-height: 200px; overflow: auto;">
                    <h6>Logs de débogage</h6>
                    <pre id="log-content" class="text-muted"></pre>
                </div>

                <div class="card-body">
                    <!-- Informations de débogage avancées -->
                    <div class="alert alert-info mb-3">
                        <h5>Informations de débogage</h5>
                        <p class="mb-1"><strong>URL actuelle :</strong> {{ url()->current() }}</p>
                        <p class="mb-1"><strong>Route actuelle :</strong> {{ Route::currentRouteName() ?? 'aucune' }}</p>
                        <p class="mb-1"><strong>Méthode HTTP attendue :</strong> POST</p>
                        <p class="mb-1"><strong>Routes disponibles :</strong></p>
                        <ul class="mb-2">
                        @php
                            $routes = collect(\Route::getRoutes())->map(function($route) {
                                return [
                                    'uri' => $route->uri(),
                                    'methods' => implode('|', $route->methods()),
                                    'name' => $route->getName()
                                ];
                            })->filter(function($route) {
                                return strpos($route['uri'], 'connected-objects') !== false;
                            });
                        @endphp
                        @foreach($routes as $route)
                            <li>{{ $route['methods'] }} - {{ $route['uri'] }} ({{ $route['name'] ?? 'sans nom' }})</li>
                        @endforeach
                        </ul>
                        <p class="mb-0"><strong>Action du formulaire :</strong> {{ url('/connected-objects') }}</p>
                    </div>
                    
                    <!-- Options de soumission du formulaire -->
                    <div class="btn-group mb-3 w-100">
                        <button id="submit-standard" class="btn btn-outline-primary">Méthode standard</button>
                        <button id="submit-fetch" class="btn btn-outline-primary">Méthode fetch</button>
                        <button id="submit-form-native" class="btn btn-outline-primary">Méthode native</button>
                    </div>

                    <!-- Ajout d'un formulaire alternatif sans JavaScript -->
                    <form id="object-form-direct" action="{{ url('/connected-objects') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <!-- Champs cachés qui seront remplis par JavaScript lors de la soumission -->
                        @foreach(['name', 'category', 'description', 'room', 'brand', 'type', 'connectivity', 'mode', 'status', 'is_automated'] as $field)
                        <input type="hidden" name="{{ $field }}" id="direct-{{ $field }}">
                        @endforeach
                    </form>
                    
                    <!-- Formulaire principal mais avec novalidate pour contrôler manuellement la validation -->
                    <form id="object-form" action="{{ url('/connected-objects') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        
                        @if(session('error'))
                            <div class="alert alert-danger mb-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Informations de débogage -->
                        <div class="alert alert-info mb-3">
                            <p class="mb-1"><strong>URL actuelle :</strong> {{ url()->current() }}</p>
                            <p class="mb-1"><strong>Route actuelle :</strong> {{ Route::currentRouteName() ?? 'aucune' }}</p>
                            <p class="mb-0"><strong>Action du formulaire :</strong> {{ url('/connected-objects') }}</p>
                        </div>
                        
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

                        <!-- Ajout de boutons de soumission alternatifs -->
                        <div class="d-grid gap-2">
                            <button type="submit" id="submit-normal" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter l'objet (standard)
                            </button>
                            <a href="{{ url('/connected-objects') }}" class="btn btn-secondary">
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
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function log(message) {
            const logElement = document.getElementById('log-content');
            const timestamp = new Date().toLocaleTimeString();
            logElement.textContent += `[${timestamp}] ${message}\n`;
            logElement.scrollTop = logElement.scrollHeight;
            console.log(message);
        }

        log('Page chargée, formulaire prêt');
        log(`URL du formulaire: ${document.getElementById('object-form').action}`);
        log('CSRF token présent: ' + (document.querySelector('meta[name="csrf-token"]') ? 'Oui' : 'Non'));
        
        // Tester la présence de la directive middleware VerifyCsrfToken
        fetch("{{ url('/connected-objects') }}", {
            method: 'HEAD',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            log(`Test de route: ${response.status} ${response.statusText}`);
            log(`En-têtes de réponse: ${JSON.stringify([...response.headers].reduce((obj, [key, val]) => {
                obj[key] = val;
                return obj;
            }, {}))}`);
        });

        // Méthode standard - seule la prévention par défaut
        document.getElementById('submit-standard').addEventListener('click', function(e) {
            e.preventDefault();
            log('Soumission via méthode standard');
            
            // Vérifier les données avant envoi
            const formData = new FormData(document.getElementById('object-form'));
            let allValid = true;
            
            // Vérifier si tous les champs requis sont remplis
            document.getElementById('object-form').querySelectorAll('[required]').forEach(el => {
                if (!el.value) {
                    log(`Champ requis manquant: ${el.name}`);
                    el.classList.add('is-invalid');
                    allValid = false;
                } else {
                    el.classList.remove('is-invalid');
                }
            });
            
            if (!allValid) {
                log('Validation échouée, formulaire non soumis');
                return;
            }
            
            log('Soumission du formulaire standard');
            document.getElementById('object-form').submit();
        });

        // Méthode utilisant fetch
        document.getElementById('submit-fetch').addEventListener('click', function(e) {
            e.preventDefault();
            log('Soumission via fetch avec suivi complet de la réponse');
            
            const formData = new FormData(document.getElementById('object-form'));
            
            fetch("{{ url('/connected-objects') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                log(`Réponse reçue: ${response.status} ${response.statusText}`);
                log(`En-têtes de réponse: ${JSON.stringify([...response.headers].reduce((obj, [key, val]) => {
                    obj[key] = val;
                    return obj;
                }, {}))}`);
                
                // Essayer de lire le corps de la réponse
                return response.text().then(text => {
                    try {
                        // Si c'est du JSON, parsons-le
                        const data = JSON.parse(text);
                        log(`Réponse JSON: ${JSON.stringify(data)}`);
                        return data;
                    } catch (e) {
                        // Sinon traitons-le comme du HTML
                        log('Réponse HTML reçue');
                        // Logique pour extraire les messages d'erreur du HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(text, 'text/html');
                        const errors = [...doc.querySelectorAll('.invalid-feedback, .alert-danger')].map(el => el.textContent.trim());
                        if (errors.length) {
                            log(`Erreurs trouvées dans HTML: ${errors.join(', ')}`);
                        }
                        if (response.redirected) {
                            log(`Redirection détectée vers: ${response.url}`);
                            window.location.href = response.url;
                        }
                        return { html: text };
                    }
                });
            })
            .catch(error => {
                log(`Erreur: ${error.message}`);
            });
        });

        // Méthode utilisant un formulaire natif direct (sans JS intermédiaire)
        document.getElementById('submit-form-native').addEventListener('click', function(e) {
            e.preventDefault();
            log('Soumission via formulaire natif');
            
            // Copier toutes les valeurs du formulaire principal vers le formulaire direct
            const formData = new FormData(document.getElementById('object-form'));
            for (let [name, value] of formData.entries()) {
                if (document.getElementById(`direct-${name}`)) {
                    document.getElementById(`direct-${name}`).value = value;
                }
            }
            
            // Soumettre le formulaire direct
            document.getElementById('object-form-direct').submit();
        });

        // Désactiver le comportement par défaut du formulaire
        document.getElementById('object-form').addEventListener('submit', function(e) {
            e.preventDefault();
            log('Soumission standard interceptée');
            document.getElementById('submit-standard').click();
        });
    });
</script>
@endpush