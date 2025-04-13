@extends('layouts.app')

@section('content')
<link href="{{ asset('css/freetour.css') }}" rel="stylesheet">

<div id="freetour-app">
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Découvrez nos Objets Connectés</h1>
            <p class="hero-subtitle">Explorez notre catalogue d'objets connectés et trouvez ceux qui correspondent à vos besoins</p>
        </div>
    </div>

    <div class="container">
        <div class="search-section">
            <div class="row">
                <div class="col-md-4">
                    <select class="search-input" v-model="category" @change="fetchObjects">
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
                           class="search-input" 
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

        <div v-else class="object-grid">
            <div v-for="object in objects" :key="object.id" class="object-card">
                <div class="object-image-container">
                    <img v-if="object.photo" :src="'/storage/' + object.photo" :alt="object.name" class="object-image">
                    <div v-else class="default-image">
                        <i class="fas fa-plug fa-3x"></i>
                    </div>
                </div>
                <div class="object-info">
                    <h4 class="object-title">@{{ object.name }}</h4>
                    <p class="object-category">@{{ object.category }}</p>
                    <p>@{{ object.description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
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
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.object-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-image {
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.object-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.object-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    background-color: white;
}

.object-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.object-info {
    padding: 15px;
}

.object-title {
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.object-category {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.search-section {
    margin: 30px 0;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.search-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.hero-section {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 30px;
    text-align: center;
}

.hero-title {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
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
