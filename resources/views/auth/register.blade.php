@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label for="surname" class="form-label">Prénom</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>
        </div>
        @error('surname')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="name" class="form-label">Nom</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
        </div>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="username" class="form-label">Nom d'utilisateur</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">
        </div>
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="gender" class="form-label">Genre</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                <option value="">Sélectionnez un genre</option>
                <option value="homme" {{ old('gender') == 'homme' ? 'selected' : '' }}>Homme</option>
                <option value="femme" {{ old('gender') == 'femme' ? 'selected' : '' }}>Femme</option>
                <option value="autre" {{ old('gender') == 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
        @error('gender')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label">Adresse e-mail</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <span class="password-toggle" onclick="togglePassword()">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            <span class="password-toggle" onclick="toggleConfirmPassword()">
                <i class="fas fa-eye"></i>
            </span>
        </div>
    </div>

    <div class="mb-4">
        <label for="photo" class="form-label">Photo de profil</label>
        <div class="input-group">
            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
        </div>
        @error('photo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="birthdate" class="form-label">Date de naissance</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
        </div>
        @error('birthdate')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="member_type" class="form-label">Rôle dans la famille</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-users"></i></span>
            <select class="form-control @error('member_type') is-invalid @enderror" id="member_type" name="member_type" required>
                <option value="">Sélectionnez un rôle</option>
                <option value="parent" {{ old('member_type') == 'parent' ? 'selected' : '' }}>Parent</option>
                <option value="enfant" {{ old('member_type') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                <option value="autre" {{ old('member_type') == 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
        @error('member_type')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required>
            <label class="form-check-label" for="terms">
                J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions d'utilisation</a>
            </label>
            @error('terms')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>S'inscrire
        </button>
    </div>
</form>

<!-- Modal des conditions d'utilisation -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Conditions d'utilisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>En vous inscrivant sur notre plateforme, vous acceptez de :</p>
                <ul>
                    <li>Respecter les règles de la communauté</li>
                    <li>Ne pas partager d'informations sensibles</li>
                    <li>Utiliser le service de manière responsable</li>
                    <li>Maintenir la confidentialité de votre compte</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<a href="{{ route('login') }}">
    Déjà inscrit ? Se connecter
</a>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function toggleConfirmPassword() {
    const passwordInput = document.getElementById('password-confirm');
    const icon = document.querySelectorAll('.password-toggle')[1].querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
