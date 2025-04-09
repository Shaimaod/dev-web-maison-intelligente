@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bienvenue sur votre Tableau de Bord Connecté</h1>
    <p>Voici les informations et les outils de votre tableau de bord connecté.</p>

    <!-- Exemple de contenu supplémentaire -->
    <p>Utilisez vos objets connectés, gérez vos paramètres, etc.</p>

    <!-- Bouton pour accéder à la page de recherche des objets -->
    <div class="mt-4">
        <a href="{{ route('connected.objects') }}" class="btn btn-primary">
            Rechercher des Objets Connectés
        </a>
    </div>
</div>
@endsection
