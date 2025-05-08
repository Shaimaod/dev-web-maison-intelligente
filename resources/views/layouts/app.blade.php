{{-- 
    Layout principal de l'application
    
    Ce fichier définit la structure HTML de base pour toutes les pages.
    Il inclut les balises meta, les liens vers les feuilles de style,
    les scripts JavaScript, la barre de navigation et le pied de page.
    
    Les vues individuelles étendent ce layout et insèrent leur contenu
    dans la section 'content'.
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
{{-- Informations de base du document --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Application de gestion de maison intelligente">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Connect’Toit') }}</title>

    {{-- Polices externes et bibliothèques CSS --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Scripts compilés et CSS via Vite --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --accent-color: #1cc88a;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: var(--light-color);
        }
        
        .navbar {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div id="app">
{{-- Barre de navigation principale --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Redirige vers le tableau de bord connecté si l'utilisateur est authentifié -->
                <a class="navbar-brand" href="{{ Auth::check() ? route('dashboard.connected') : url('/') }}">
                    {{ config('app.name', 'Connect’Toit') }}
                </a>

                {{-- Bouton hamburger pour la navigation mobile --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

{{-- Contenu de la barre de navigation --}}
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {{-- Liens de navigation à gauche --}}
                    <ul class="navbar-nav me-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('freetour') }}">
                                    <i class="fas fa-eye me-1"></i>Découvrir les objets connectés
                                </a>
                            </li>
                        @endguest
                    </ul>

                    {{-- Liens de navigation à droite --}}
                    <ul class="navbar-nav ms-auto">
                        {{-- Liens d'authentification dynamiques --}}
                        @guest
{{-- Liens pour les utilisateurs non connectés --}}
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

{{-- Lien d'inscription si activé --}}
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- Menu utilisateur pour les personnes connectées --}}
                            <li class="nav-item dropdown">
{{-- Bouton du menu déroulant --}}
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

{{-- Contenu du menu déroulant --}}
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Admin Dashboard Link (only visible to admins) -->
                                    @if(Auth::user()->role === 'admin') <!-- Check if user is admin -->
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            {{ __('Admin Dashboard') }}
                                        </a>
                                    @endif

                                    <!-- Dashboard Link (for normal users) -->
                                    <a class="dropdown-item" href="{{ route('dashboard.connected') }}">
                                        {{ __('Dashboard') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        {{ __('Profile') }}
                                    </a>

                                    <!-- Logout option -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

{{-- Contenu principal de la page --}}
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Connect’Toit. Tous droits réservés.</p>
    </footer>
    @stack('scripts')
</body>
</html>
