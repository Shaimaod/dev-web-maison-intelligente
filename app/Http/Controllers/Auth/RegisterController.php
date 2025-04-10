<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    // La route où l'utilisateur sera redirigé après l'inscription
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validation des données d'inscription.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['nullable', 'string'],
            'birthdate' => ['required', 'date'],
            'member_type' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Crée un nouvel utilisateur dans la base de données.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Traitement de la photo si elle est téléchargée
        $photoPath = null;
        if (request()->hasFile('photo')) {
            $photoPath = request()->file('photo')->store('profiles', 'public');
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'birthdate' => $data['birthdate'],
            'member_type' => $data['member_type'],
            'photo' => $photoPath,
            'password' => Hash::make($data['password']),
            'points' => 0, // Initialisation des points à 0
            'level' => 'débutant', // Initialisation du niveau
        ]);

        // Mettre à jour le niveau (même si c'est débutant, pour la cohérence)
        $user->updateLevel();

        return $user;
    }

    /**
     * Méthode d'enregistrement de l'utilisateur.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validation des données d'inscription
        $this->validator($request->all())->validate();
        
        // Création de l'utilisateur
        $user = $this->create($request->all());

        // Connexion de l'utilisateur
        Auth::login($user);

        // Envoi de l'email de vérification
        $user->sendEmailVerificationNotification(); // Utilise la méthode par défaut pour envoyer l'email

        // Rediriger l'utilisateur vers la page de confirmation avant la vérification de l'email
        return redirect()->route('verification.notice');
    }
}
