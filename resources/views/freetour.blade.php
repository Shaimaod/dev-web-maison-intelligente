<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freetour - Maison Connectée</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])  <!-- Assurez-vous que Vue.js est inclus -->
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Maison Connectée</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/freetour">Freetour</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="container mt-5">
            <h1>Bienvenue sur Freetour</h1>
            <p>Rechercher des objets connectés :</p>

            <!-- Barre de recherche en temps réel -->
            <div class="mb-3">
                <input v-model="query" @input="fetchObjects" type="text" class="form-control" placeholder="Rechercher un objet">
            </div>

            <!-- Filtres -->
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
            <div class="mt-4">
                <h4>Résultats :</h4>
                <!-- Utilisation de Vue.js pour afficher dynamiquement les objets -->
                <ul>
                    <li v-for="object in objects" :key="object.id">
                        <!-- Vue.js va gérer l'affichage des objets -->
                        <span v-text="object.name"></span> - 
                        <span v-text="object.description"></span> (
                        <span v-text="object.category"></span>)
                    </li>
                </ul>
                <p v-if="objects.length === 0">Aucun objet trouvé.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Popper.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>
