<?php

/**
 * Routes Web - Définit toutes les routes accessibles via le navigateur
 *
 * Ce fichier contient toutes les routes principales de l'application :
 * - Routes publiques (accueil, authentification)
 * - Routes protégées nécessitant une authentification
 * - Routes administratives
 * - Routes de gestion des profils utilisateurs
 * - Routes de gestion des objets connectés
 */

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
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\DeletionRequestController;
use App\Http\Controllers\EnergyController;

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
Route::get('/', [HomeController::class, 'index']);

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
    
    // Routes pour la gestion des objets connectés
    Route::get('/object/{id}', [ConnectedObjectController::class, 'show'])->name('object.show');
    Route::get('/object/{id}/edit', [ConnectedObjectController::class, 'edit'])->name('object.edit');
    Route::put('/object/{id}', [ConnectedObjectController::class, 'update'])->name('object.update');
    Route::put('/object/{id}/updateForEdit', [ConnectedObjectController::class, 'updateForEdit'])->name('object.updateForEdit');
    Route::get('/connected-objects/create', [ConnectedObjectController::class, 'create'])->name('connected.objects.create');
    Route::post('/connected-objects', [ConnectedObjectController::class, 'store'])->name('connected.objects.store');
    Route::post('/object/{id}/request-deletion', [DeletionRequestController::class, 'requestDeletion'])->name('object.request-deletion');

    // Route pour la page des objets connectés
    Route::get('/connected-objects', [ConnectedObjectController::class, 'showConnectedObjects'])->name('connected.objects');

    // Ajouter cette route quelque part dans le groupe middleware 'auth'
    Route::post('/connected-objects/create-default', [App\Http\Controllers\DefaultObjectController::class, 'createDefault'])
        ->name('connected.objects.create-default');
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

    // Les routes 'freetour' est disponible sans authentification
    Route::get('/freetour', [ConnectedObjectController::class, 'index'])->name('freetour');
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

    // Routes pour la gestion de l'expérience utilisateur
    Route::get('/experience', [ExperienceController::class, 'index'])->name('admin.experience');
    Route::post('/experience/update', [ExperienceController::class, 'updatePoints'])->name('admin.experience.update');
    Route::post('/experience/user/{user}', [ExperienceController::class, 'updateUserPoints'])->name('admin.experience.update-user');
    
    // Routes pour la gestion des demandes de suppression
    Route::get('/deletion-requests', [DeletionRequestController::class, 'index'])->name('admin.deletion-requests.index');
    Route::get('/deletion-requests/{request}', [DeletionRequestController::class, 'show'])->name('admin.deletion-requests.show');
    Route::post('/deletion-requests/{deletionRequest}/process', [DeletionRequestController::class, 'process'])->name('admin.deletion-requests.process');
});

/*
|--------------------------------------------------------------------------
| Routes pour la gestion des emails autorisés (accessible uniquement aux admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/authorized-users', [App\Http\Controllers\AuthorizedUserController::class, 'index'])
         ->name('authorized-users.index');
    Route::post('/admin/authorized-users', [App\Http\Controllers\AuthorizedUserController::class, 'store'])
         ->name('authorized-users.store');
    Route::delete('/admin/authorized-users/{id}', [App\Http\Controllers\AuthorizedUserController::class, 'destroy'])
         ->name('authorized-users.destroy');
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
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified(); // Marque l'email comme vérifié
            
            // Débogage pour confirmer la mise à jour
            \Illuminate\Support\Facades\Log::info("Email vérifié pour l'utilisateur: " . $user->id . " à " . now()->toDateTimeString());
            
            // S'assurer que la colonne email_verified_at est bien mise à jour
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => now()]);
        }
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

    // Rechercher des profils
    Route::get('/profiles/search', [ProfileController::class, 'search'])->name('profiles.search');
    
    // Afficher un profil spécifique
    Route::get('/profiles/{user}', [ProfileController::class, 'showProfile'])->name('profiles.show');

    // Modifier le profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route pour afficher l'historique des actions de l'utilisateur
    Route::get('/profile/activity', [ProfileController::class, 'activity'])->name('profile.activity');
});

/*
|--------------------------------------------------------------------------
| Routes pour la gestion de l'énergie
|--------------------------------------------------------------------------
| Ces routes sont accessibles uniquement aux utilisateurs authentifiés.
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/energy/set-goal', [EnergyController::class, 'setGoal'])->name('energy.set-goal');
    Route::get('/energy/history', [EnergyController::class, 'history'])->name('energy.history');
    Route::get('/energy/details', [EnergyController::class, 'details'])->name('energy.details');
    
    // Route pour afficher l'historique des actions de l'utilisateur
    Route::get('/profile/activity', [ProfileController::class, 'activity'])->name('profile.activity');
});