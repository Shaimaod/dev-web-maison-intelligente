@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nom --}}
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nom</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Prénom --}}
                        <div class="row mb-3">
                            <label for="surname" class="col-md-4 col-form-label text-md-end">Prénom</label>
                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror"
                                    name="surname" value="{{ old('surname') }}" required>
                                @error('surname')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Pseudonyme --}}
                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">Pseudonyme</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                    name="username" value="{{ old('username') }}" required>
                                @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Adresse Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Genre --}}
                        <div class="row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-end">Genre</label>
                            <div class="col-md-6">
                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Homme" {{ old('gender') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="Femme" {{ old('gender') == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    <option value="Autre" {{ old('gender') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('gender')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Date de naissance --}}
                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">Date de naissance</label>
                            <div class="col-md-6">
                                <input id="birthdate" type="date"
                                    class="form-control @error('birthdate') is-invalid @enderror" name="birthdate"
                                    value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Type de membre --}}
                        <div class="row mb-3">
                            <label for="member_type" class="col-md-4 col-form-label text-md-end">Type de membre</label>
                            <div class="col-md-6">
                                <select name="member_type" id="member_type" class="form-select @error('member_type') is-invalid @enderror" required>
                                    <option value="">Choisissez</option>
                                    <option value="père" {{ old('member_type') == 'père' ? 'selected' : '' }}>Père</option>
                                    <option value="mère" {{ old('member_type') == 'mère' ? 'selected' : '' }}>Mère</option>
                                    <option value="enfant" {{ old('member_type') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                                    <option value="autre" {{ old('member_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('member_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Photo --}}
                        <div class="row mb-3">
                            <label for="photo" class="col-md-4 col-form-label text-md-end">Photo de profil</label>
                            <div class="col-md-6">
                                <input id="photo" type="file"
                                    class="form-control @error('photo') is-invalid @enderror" name="photo">
                                @error('photo')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Mot de passe --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required>
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirmation mot de passe --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmer mot de passe</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        {{-- Bouton --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    S'inscrire
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
