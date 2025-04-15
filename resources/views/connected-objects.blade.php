@extends('layouts.user')

@section('content')
<div id="connected-objects-app">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Objets Connectés</h1>
            @if(auth()->user()->level === 'advanced' || auth()->user()->level === 'expert')
            <a href="{{ route('connected.objects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Ajouter un objet
            </a>
            @endif
        </div>
        <div class="row g-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Objets Connectés</h1>
            @if(auth()->user()->level === 'advanced' || auth()->user()->level === 'expert')
            <a href="{{ route('connected.objects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Ajouter un objet
            </a>
            @endif
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" 
                       class="form-control" 
                       v-model="query" 
                       @input="debounceSearch"
                       @input="debounceSearch"
                       placeholder="Rechercher un objet...">
            </div>
            <div class="col-md-4">
                <select class="form-select" v-model="category" @change="fetchObjects">
                <select class="form-select" v-model="category" @change="fetchObjects">
                    <option value="">Toutes les catégories</option>
                    <option value="Éclairage">Éclairage</option>
                    <option value="Climatisation">Climatisation</option>
                    <option value="Sécurité">Sécurité</option>
                    <option value="Électroménager">Électroménager</option>
                    <option value="Audio">Audio</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" @click="fetchObjects">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>

        <div v-else-if="objects.length === 0" class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>Aucun objet trouvé</h4>
            <p class="text-muted">Essayez de modifier vos critères de recherche</p>
        </div>

        <div v-else class="row g-4">
            <div v-for="item in objects" :key="item.id" class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-container position-relative">
        <div v-else class="row g-4">
            <div v-for="item in objects" :key="item.id" class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-container position-relative">
                        <img :src="getObjectImage(item)" class="card-img-top" :alt="item.name">
                        <span :class="['status-badge', item.status === 'Actif' ? 'bg-success' : 'bg-danger']" v-text="item.status"></span>
                        <span :class="['status-badge', item.status === 'Actif' ? 'bg-success' : 'bg-danger']" v-text="item.status"></span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-info" v-text="item.category"></span>
                            <span class="badge bg-secondary" v-text="item.room"></span>
                        </div>
                        <h5 class="card-title" v-text="item.name"></h5>
                        <p class="card-text" v-text="item.description"></p>
                        <div class="mt-3">
                            <p class="mb-1"><i class="fas fa-plug me-2"></i><span v-text="item.connectivity"></span></p>
                            <p v-if="item.current_temp" class="mb-1">
                                <i class="fas fa-thermometer-half me-2"></i><span v-text="item.current_temp"></span>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a :href="'/object/' + item.id" class="btn btn-primary w-100">
                            <i class="fas fa-info-circle me-2"></i>Plus d'informations
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="lastPage > 1" class="d-flex justify-content-center mt-4">
            <nav aria-label="Pagination">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage <= 1 }">
                        <a class="page-link" href="#" @click.prevent="prevPage">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item" v-for="page in lastPage" :key="page" :class="{ active: currentPage === page }">
                        <a class="page-link" href="#" @click.prevent="goToPage(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage >= lastPage }">
                        <a class="page-link" href="#" @click.prevent="nextPage">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que Vue est disponible
    if (typeof window.Vue === 'undefined') {
        console.error('Vue.js n\'est pas chargé');
        return;
    }

    const defaultImages = {
        'Éclairage': '/images/default-objects/lighting.svg',
        'Climatisation': '/images/default-objects/thermostat.svg',
        'Sécurité': '/images/default-objects/security.svg',
        'Électroménager': '/images/default-objects/appliance.svg',
        'Audio': '/images/default-objects/audio.svg'
    };

    const app = window.Vue.createApp({
        data() {
            return {
                objects: [],
                loading: true,
                query: '',
                category: '',
                currentPage: 1,
                lastPage: 1,
                error: null,
                searchTimeout: null
                error: null,
                searchTimeout: null
            }
        },
        methods: {
            getObjectImage(item) {
                if (item.image) {
                    // Si l'image est une URL complète, la retourner directement
                    if (item.image.startsWith('http')) {
                        return item.image;
                    }
                    // Sinon, utiliser le chemin stocké directement
                    return `/storage/${item.image}`;
                    // Sinon, utiliser le chemin stocké directement
                    return `/storage/${item.image}`;
                }
                return defaultImages[item.category] || '/images/default-objects/default.svg';
            },
            formatDate(date) {
                if (!date) return 'Jamais';
                return new Date(date).toLocaleString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },
            debounceSearch() {
                // Annuler le timeout précédent s'il existe
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }
                
                // Définir un nouveau timeout pour retarder la recherche
                this.searchTimeout = setTimeout(() => {
                    this.currentPage = 1; // Réinitialiser à la première page
                    this.fetchObjects();
                }, 500); // Attendre 500ms après la dernière frappe
            },
            debounceSearch() {
                // Annuler le timeout précédent s'il existe
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }
                
                // Définir un nouveau timeout pour retarder la recherche
                this.searchTimeout = setTimeout(() => {
                    this.currentPage = 1; // Réinitialiser à la première page
                    this.fetchObjects();
                }, 500); // Attendre 500ms après la dernière frappe
            },
            async fetchObjects() {
                console.log('Début de fetchObjects');
                this.loading = true;
                this.error = null;
                
                const params = new URLSearchParams({
                    page: this.currentPage,
                    query: this.query,
                    category: this.category
                });
                this.error = null;
                
                const params = new URLSearchParams({
                    page: this.currentPage,
                    query: this.query,
                    category: this.category
                });

                console.log('Envoi de la requête à /get-objects avec les paramètres:', params.toString());
                const response = await fetch(`/get-objects?${params.toString()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                console.log('Réponse reçue', response);
                
                if (response.status === 401) {
                    // Rediriger vers la page de connexion si non authentifié
                    window.location.href = '/login';
                    return;
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Données reçues', data);
                
                this.objects = data.data;
                this.currentPage = data.current_page;
                this.lastPage = data.last_page;
                this.loading = false;
                console.log('Envoi de la requête à /get-objects avec les paramètres:', params.toString());
                const response = await fetch(`/get-objects?${params.toString()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                console.log('Réponse reçue', response);
                
                if (response.status === 401) {
                    // Rediriger vers la page de connexion si non authentifié
                    window.location.href = '/login';
                    return;
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Données reçues', data);
                
                this.objects = data.data;
                this.currentPage = data.current_page;
                this.lastPage = data.last_page;
                this.loading = false;
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.fetchObjects();
                }
            },
            nextPage() {
                if (this.currentPage < this.lastPage) {
                    this.currentPage++;
                    this.fetchObjects();
                }
            },
            goToPage(page) {
                this.currentPage = page;
                this.fetchObjects();
            }
        },
        mounted() {
            console.log('Vue app mounted');
            this.fetchObjects();
        }
    });

    // Vérifier si l'élément existe avant de monter l'application
    const appElement = document.getElementById('connected-objects-app');
    if (appElement) {
        console.log('Mounting Vue app to #connected-objects-app');
        app.mount('#connected-objects-app');
    } else {
        console.error('Element #connected-objects-app not found');
    }
});
</script>
@endpush

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
}

.card-img-container {
    height: 200px;
    overflow: hidden;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 15px;
    color: white;
    font-size: 0.8rem;
}

.page-link {
    color: var(--primary-color);
}

.page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>
@endsection
