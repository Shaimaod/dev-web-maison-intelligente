@extends('layouts.app')

@section('content')
<link href="{{ asset('css/freetour.css') }}" rel="stylesheet">

<div id="freetour-app">
    <div class="hero-section text-white text-center py-5" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
        <div class="container">
            <h1 class="hero-title display-4">Découvrez nos Objets Connectés</h1>
            <p class="hero-subtitle lead">Explorez notre catalogue d'objets connectés et trouvez ceux qui correspondent à vos besoins</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="search-section bg-light p-4 rounded shadow-sm mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" v-model="category" @change="fetchObjects">
                        <option value="">Toutes les catégories</option>
                        <option value="Éclairage">Éclairage</option>
                        <option value="Climatisation">Climatisation</option>
                        <option value="Sécurité">Sécurité</option>
                        <option value="Électroménager">Électroménager</option>
                        <option value="Audio">Audio</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" 
                           class="form-control" 
                           v-model="query" 
                           @input="debounceSearch"
                           placeholder="Rechercher un objet...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" @click="fetchObjects">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </div>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>

        <div v-else-if="error" class="alert alert-danger text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
            <h4>Une erreur est survenue</h4>
            <p>@{{ error }}</p>
            <button class="btn btn-primary mt-3" @click="fetchObjects">
                <i class="fas fa-redo me-2"></i>Réessayer
            </button>
        </div>

        <div v-else-if="objects.length === 0" class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>Aucun objet trouvé</h4>
            <p class="text-muted">Essayez de modifier vos critères de recherche</p>
        </div>

        <div v-else class="row g-4">
            <div v-for="object in objects" :key="object.id" class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="object-image-container">
                        <img v-if="object.photo" :src="'/storage/' + object.photo" :alt="object.name" class="card-img-top">
                        <div v-else class="default-image d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-plug fa-3x text-muted"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">@{{ object.name }}</h5>
                        <p class="card-text text-muted">@{{ object.category }}</p>
                        <p class="card-text">@{{ object.description }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="badge" :class="object.status === 'Actif' ? 'bg-success' : 'bg-danger'">
                            @{{ object.status }}
                        </span>
                        <a :href="'/object/' + object.id" class="btn btn-primary">
                            <i class="fas fa-info-circle me-2"></i>Détails
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

<style>
.object-image-container {
    height: 200px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
}

.default-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.hero-section {
    margin-bottom: 30px;
}

.search-section {
    margin-bottom: 30px;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que Vue est disponible
    if (typeof window.Vue === 'undefined') {
        console.error('Vue.js n\'est pas chargé');
        return;
    }

    const app = window.Vue.createApp({
        data() {
            return {
                objects: [],
                query: '',
                category: '',
                currentPage: 1,
                lastPage: 1,
                loading: false,
                error: null,
                searchTimeout: null
            }
        },
        methods: {
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
                
                try {
                    const params = new URLSearchParams({
                        page: this.currentPage,
                        query: this.query,
                        category: this.category
                    });

                    console.log('Envoi de la requête à /api/objects avec les paramètres:', params.toString());
                    const response = await fetch(`/api/objects?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    console.log('Réponse reçue', response);
                    
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Données reçues', data);
                    
                    this.objects = data.data;
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                } catch (error) {
                    console.error('Erreur lors de la récupération des objets:', error);
                    this.error = 'Impossible de charger les objets. Veuillez réessayer plus tard.';
                } finally {
                    this.loading = false;
                }
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
    const appElement = document.getElementById('freetour-app');
    if (appElement) {
        console.log('Mounting Vue app to #freetour-app');
        app.mount('#freetour-app');
    } else {
        console.error('Element #freetour-app not found');
    }
});
</script>
@endpush
@endsection
