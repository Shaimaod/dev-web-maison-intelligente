<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConnectedObjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorizedUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RedirectIfAuthenticated;

/*
|--------------------------------------------------------------------------
| Authentification & Vérification de l'email
|--------------------------------------------------------------------------
| Routes pour l'authentification et la vérification de l'email.
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Routes Publiques (Accueil)
|--------------------------------------------------------------------------
| Routes accessibles à tous les utilisateurs, même non authentifiés.
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Routes Utilisateurs Authentifiés et Vérifiés
|--------------------------------------------------------------------------
| Ces routes sont accessibles uniquement aux utilisateurs authentifiés et vérifiés.
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route pour accéder à la page freetour
    Route::get('/freetour', [ConnectedObjectController::class, 'index'])->name('freetour');
    
    // Route pour récupérer les objets filtrés via l'API
    Route::get('/get-objects', [ConnectedObjectController::class, 'getObjects'])->name('getObjects');  // Recherche des objets filtrés

    // Route pour accéder à la page dashboard.connected
    Route::get('/dashboard.connected', [ConnectedObjectController::class, 'dashboardConnected'])->name('dashboard.connected');  // Nouvelle méthode pour afficher dashboard
});

/*
|--------------------------------------------------------------------------
| Routes Utilisateurs Non Connectés
|--------------------------------------------------------------------------
| Routes accessibles uniquement aux utilisateurs non connectés.
| Ces routes incluent l'inscription, la connexion et certaines autres.
*/
Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Les routes 'freetour' et 'dashboard.connected' sont disponibles aussi sans authentification
    Route::get('/freetour', [ConnectedObjectController::class, 'index'])->name('freetour');
    Route::get('/dashboard.connected', [ConnectedObjectController::class, 'dashboardConnected'])->name('dashboard.connected');
});

/*
|--------------------------------------------------------------------------
| Routes Administrateurs
|--------------------------------------------------------------------------
| Ces routes sont accessibles uniquement aux administrateurs pour gérer les utilisateurs.
*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/verify/{id}', [AdminController::class, 'verify'])->name('admin.verify');
    Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::put('/users/{id}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::post('/promote/{id}', [AdminController::class, 'promote'])->name('admin.promote');

    // Gestion des utilisateurs autorisés
    Route::get('/authorized-users', [AuthorizedUserController::class, 'index'])->name('authorized-users.index');
    Route::post('/authorized-users', [AuthorizedUserController::class, 'store'])->name('authorized-users.store');
    Route::delete('/authorized-users/{id}', [AuthorizedUserController::class, 'destroy'])->name('authorized-users.destroy');
});

/*
|--------------------------------------------------------------------------
| Route de Vérification de l'Email
|--------------------------------------------------------------------------
| Cette route permet à l'utilisateur de cliquer sur le lien envoyé pour vérifier leur email.
| Elle vérifie le lien signé et marque l'email comme vérifié.
*/
Route::get('email/verify', function () {
    // Affichage d'un message confirmant que l'email a été envoyé
    return view('auth.verify');
})->name('verification.notice');

/*
|--------------------------------------------------------------------------
| Route de Vérification de l'Email (Lien de validation)
|--------------------------------------------------------------------------
| Cette route vérifie le lien de validation et marque l'email comme vérifié si le hash correspond.
*/
Route::get('email/verify/{id}/{hash}', function ($id, $hash) {
    // Trouver l'utilisateur par son ID
    $user = \App\Models\User::findOrFail($id);

    // Vérifier si le hash correspond à l'email de l'utilisateur
    if (sha1($user->getEmailForVerification()) === $hash) {
        $user->markEmailAsVerified(); // Marque l'email comme vérifié
        return redirect('/home')->with('status', 'Votre email a été vérifié !');
    }

    // Si le hash ne correspond pas, rediriger vers la page de connexion
    return redirect('/login')->with('error', 'Lien de vérification invalide.');
})->middleware(['auth', 'signed'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Routes Profil
|--------------------------------------------------------------------------
| Routes pour afficher et mettre à jour le profil de l'utilisateur.
*/
Route::middleware(['auth'])->group(function () {
    // Afficher le profil de l'utilisateur
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Mettre à jour le profil de l'utilisateur
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Ajouter la route pour la nouvelle page
Route::get('/connected-objects', [ConnectedObjectController::class, 'showConnectedObjects'])->name('connected.objects');