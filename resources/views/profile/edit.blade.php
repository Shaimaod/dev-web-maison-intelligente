@extends('layouts.user')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Photo de profil -->
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle border" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" style="width: 40px; height: 40px;" onclick="document.getElementById('photo').click()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <input type="file" id="photo" name="photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>

                        <!-- Nom et Prénom -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="surname" class="form-label">Prénom</label>
                                <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                                @error('surname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nom d'utilisateur -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Genre -->
                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_m" value="male" {{ old('gender', $user->gender) === 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_m">Homme</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_f" value="female" {{ old('gender', $user->gender) === 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_f">Femme</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_o" value="other" {{ old('gender', $user->gender) === 'other' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_o">Autre</label>
                                </div>
                            </div>
                        </div>

                        <!-- Date de naissance -->
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}">
                            @error('birthdate')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type de membre -->
                        <div class="mb-3">
                            <label for="member_type" class="form-label">Type de membre</label>
                            <select class="form-select @error('member_type') is-invalid @enderror" id="member_type" name="member_type">
                                <option value="parent" {{ old('member_type', $user->member_type) === 'parent' ? 'selected' : '' }}>Parent</option>
                                <option value="child" {{ old('member_type', $user->member_type) === 'child' ? 'selected' : '' }}>Enfant</option>
                                <option value="other" {{ old('member_type', $user->member_type) === 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('member_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-text">Laissez vide pour ne pas changer le mot de passe</div>
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.rounded-circle');
            if (img.tagName === 'IMG') {
                img.src = e.target.result;
            } else {
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.classList.add('rounded-circle');
                newImg.style.width = '150px';
                newImg.style.height = '150px';
                newImg.style.objectFit = 'cover';
                img.parentNode.replaceChild(newImg, img);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush