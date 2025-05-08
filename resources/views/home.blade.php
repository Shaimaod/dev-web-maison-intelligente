{{-- 
    Vue de la page d'accueil de l'application
    
    Cette page est la première vue que voit un utilisateur authentifié.
    Elle présente des informations générales sur l'application et 
    des raccourcis vers les fonctionnalités principales.
    
    Étend la mise en page principale et définit le contenu spécifique
    pour cette section.
--}}

@extends('layouts.app')

@section('content')
{{-- Contenu de la page d'accueil --}}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
{{-- Message de confirmation d'authentification réussie --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
