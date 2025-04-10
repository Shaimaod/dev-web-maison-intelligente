<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard.connected';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Vérification de l'email
            if (!$user->hasVerifiedEmail()) {
                // Déconnexion de l'utilisateur
                Auth::logout();

                // Rediriger avec un message d'erreur
                return redirect()->route('login')->withErrors([
                    'email' => 'Veuillez vérifier votre adresse e-mail avant de vous connecter.'
                ]);
            }

            // Ajouter des points d'expérience à chaque connexion
            $user->addPoints('login');
            
            // Mettre à jour le niveau de l'utilisateur
            $user->updateLevel();

            // Sauvegarder l'utilisateur avec les nouveaux points et le niveau mis à jour
            $user->save();

            // Rediriger vers le tableau de bord connecté
            return redirect()->route('dashboard.connected');
        }

        // En cas d'échec de la tentative de connexion
        return redirect()->route('login')->withErrors([
            'email' => 'Les informations d\'identification sont incorrectes.'
        ]);
    }
}



