@extends('layouts.app')

@section('content')
    <!-- Contenu principal -->
    <div class="container mt-5">
        <h1>Objets Connectés</h1>
        <p>Rechercher des objets connectés :</p>

        <!-- Formulaire de recherche -->
        <div class="mb-3">
            <input v-model="query" @input="fetchObjects" type="text" class="form-control" placeholder="Rechercher un objet">
        </div>

        <!-- Filtre de catégorie -->
        <div class="mb-3">
            <select v-model="category" @change="fetchObjects" class="form-control">
                <option value="">Sélectionner une catégorie</option>
                <option value="Éclairage">Éclairage</option>
                <option value="Climatisation">Climatisation</option>
                <option value="Sécurité">Sécurité</option>
                <option value="Électroménager">Électroménager</option>
                <option value="Audio">Audio</option>
            </select>
        </div>

        <!-- Affichage des objets -->
        <div v-if="objects.length" class="mt-4">
            <h4>Résultats :</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div v-for="item in objects" :key="item.id" class="col">
                    <div class="card">
                        <img :src="'/storage/' + item.photo" class="card-img-top" alt="image de l'objet">
                        <div class="card-body">
                            <h5 class="card-title">@{{ item.name }}</h5> <!-- Utilisez @{{ }} pour Vue.js -->
                            <p class="card-text">@{{ item.description }}</p> <!-- Utilisez @{{ }} pour Vue.js -->
                            <ul class="list-unstyled">
                                <li><strong>Catégorie:</strong> @{{ item.category }}</li> <!-- Utilisez @{{ }} pour Vue.js -->
                                <li><strong>Marque:</strong> @{{ item.brand }}</li> <!-- Utilisez @{{ }} pour Vue.js -->
                                <li><strong>Statut:</strong> @{{ item.status }}</li> <!-- Utilisez @{{ }} pour Vue.js -->
                            </ul>
                            <a :href="'/object/' + item.id" class="btn btn-primary">Voir Détails</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 text-center">
                <button @click="prevPage" :disabled="currentPage <= 1" class="btn btn-secondary">Précédent</button>
                <span class="mx-2">Page @{{ currentPage }} sur @{{ lastPage }}</span>
                <button @click="nextPage" :disabled="currentPage >= lastPage" class="btn btn-secondary">Suivant</button>
            </div>
        </div>

        <!-- Message si aucun objet n'est trouvé -->
        <div v-else class="mt-4 text-center">
            <p>Aucun objet trouvé. Essayez une autre recherche.</p>
        </div>
    </div>
@endsection
