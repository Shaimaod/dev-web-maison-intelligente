<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

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

            // Mettre à jour la dernière connexion
            $user->last_login_at = now();
            $user->save();

            // Ajouter des points d'expérience à chaque connexion
            $user->addPoints('login');
            
            // Mettre à jour le niveau de l'utilisateur
            $user->updateLevel();

            // Sauvegarder l'utilisateur avec les nouveaux points et le niveau mis à jour
            $user->save();

            // Enregistrer l'activité de connexion
            ActivityLog::create([
                'user_id' => $user->id,
                'action_type' => 'login',
                'description' => 'Connexion réussie',
                'details' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);

            // Rediriger vers le tableau de bord connecté
            return redirect()->route('dashboard.connected');
        }

        // En cas d'échec de la tentative de connexion
        return redirect()->route('login')->withErrors([
            'email' => 'Les informations d\'identification sont incorrectes.'
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            // Enregistrer l'activité de déconnexion
            ActivityLog::create([
                'user_id' => $user->id,
                'action_type' => 'logout',
                'description' => 'Déconnexion',
                'details' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {
        Log::debug('User authenticated', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended($this->redirectPath());
    }
}



