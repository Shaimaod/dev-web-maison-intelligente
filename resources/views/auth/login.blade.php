@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <h1 class="h3 mb-3 fw-normal">Connectez-vous à Connect’Toit</h1>

    <div class="mb-4">
        <label for="email" class="form-label">Adresse e-mail</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
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
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Se souvenir de moi
            </label>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </button>
    </div>
</form>
@endsection

@section('footer')
@if (Route::has('password.request'))
    <a href="{{ route('password.request') }}">
        Mot de passe oublié ?
    </a>
@endif
<span class="mx-2">|</span>
<a href="{{ route('register') }}">
    Créer un compte
</a>
<p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }} Connect’Toit. Tous droits réservés.</p>
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
</script>
@endpush
