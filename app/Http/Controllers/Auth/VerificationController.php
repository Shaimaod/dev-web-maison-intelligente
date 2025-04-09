<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    // Méthode pour afficher la vue de vérification
    public function show(Request $request)
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $request->user();
        
        // Générer l'URL de vérification
        $verificationUrl = route('verification.verify', [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification())
        ]);
        
        // Passer l'URL de vérification et l'utilisateur à la vue
        return view('auth.verify', ['verificationUrl' => $verificationUrl, 'user' => $user]);
    }
}


