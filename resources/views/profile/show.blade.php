@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h3>Mon Profil</h3>
                </div>

                <div class="card-body">
                    <!-- Affichage des messages de succès ou d'erreur -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Affichage du niveau et de la barre d'expérience -->
                    <div class="mb-4">
                        <h5>Niveau actuel : <strong>{{ ucfirst($user->level) }}</strong></h5>
                        <p>Points d'expérience : <strong>{{ $user->points }}</strong></p>

                        <!-- Définir les seuils des niveaux -->
                        @php
                            // Définir les seuils des niveaux
                            $nextLevelThresholds = [
                                'beginner' => 10,       // Débutant : 10 points max
                                'intermediate' => 20,   // Intermédiaire : 20 points max
                                'advanced' => 30,      // Avancé : 30 points max
                                'expert' => 30,        // Expert : 30 points (pas de progression après)
                            ];

                            // Déterminer le niveau actuel de l'utilisateur
                            $level = 'beginner';
                            if ($user->points > 10) {
                                $level = 'intermediate';
                            }
                            if ($user->points > 20) {
                                $level = 'advanced';
                            }
                            if ($user->points > 30) {
                                $level = 'expert';
                            }

                            // Calcul de la progression vers le niveau suivant
                            $nextLevelPoints = $nextLevelThresholds[$level];
                            $progress = min($user->points, $nextLevelPoints); // Calculer les points utilisés pour la barre
                            $progressPercent = ($progress / $nextLevelPoints) * 100; // Calculer la largeur de la barre
                        @endphp

                        <!-- Afficher la barre d'expérience -->
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%" aria-valuenow="{{ $user->points }}" aria-valuemin="0" aria-valuemax="{{ $nextLevelPoints }}"></div>
                        </div>

                        <p>Progression vers le niveau suivant : <strong>{{ $nextLevelPoints - $user->points }} points restants</strong></p>
                    </div>

                    <!-- Formulaire de modification du profil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex align-items-center mb-3">
                                <!-- Affichage de la photo actuelle si elle existe -->
                                @if ($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="img-thumbnail" style="width: 100px; height: 100px; border-radius: 50%; margin-right: 20px;">
                                @else
                                    <span class="text-muted">Aucune photo</span>
                                @endif

                                <label for="photo" class="font-weight-bold mr-3" style="min-width: 150px;">Photo de profil :</label>
                                <input type="file" class="form-control-file" id="photo" name="photo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Nom :</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="surname" class="font-weight-bold">Prénom :</label>
                            <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="username" class="font-weight-bold">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Adresse e-mail :</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Champ pour modifier le mot de passe -->
                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Nouveau mot de passe :</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide si vous ne voulez pas changer">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold">Confirmer le mot de passe :</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmer le nouveau mot de passe">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
