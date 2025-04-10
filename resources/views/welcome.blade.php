@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0">Bienvenue sur Maison Connectée</h1>
                </div>
                <div class="card-body">
                    <p class="lead mb-4">Gérez tous vos objets connectés depuis une seule interface</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Créer un compte
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="features-section py-5">
        <h2 class="text-center mb-5">Nos fonctionnalités</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-plug feature-icon"></i>
                    <h3>Gestion centralisée</h3>
                    <p>Contrôlez tous vos objets connectés depuis une seule interface intuitive.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-chart-line feature-icon"></i>
                    <h3>Suivi en temps réel</h3>
                    <p>Visualisez l'état et les performances de vos appareils en temps réel.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h3>Sécurité renforcée</h3>
                    <p>Protégez votre maison avec des fonctionnalités de sécurité avancées.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-section text-center py-5">
        <h2>Prêt à commencer ?</h2>
        <p class="lead">Rejoignez notre communauté et transformez votre maison en maison connectée.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-rocket me-2"></i>Commencer maintenant
        </a>
    </div>
</div>
@endsection
