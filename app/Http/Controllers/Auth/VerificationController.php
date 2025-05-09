<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        if ($request->route('id') != $request->user()->getKey()) {
            Log::warning('Verification attempt with mismatched user ID');
            return redirect($this->redirectPath())->with('error', 'ID utilisateur incorrect.');
        }

        if ($request->user()->hasVerifiedEmail()) {
            Log::info('User email already verified: ' . $request->user()->id);
            return redirect($this->redirectPath())->with('status', 'Email déjà vérifié.');
        }

        if ($request->user()->markEmailAsVerified()) {
            Log::info('Successfully verified email for user: ' . $request->user()->id);
            event(new \Illuminate\Auth\Events\Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('status', 'Email vérifié avec succès!');
    }
    
    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            Log::info('User tried to resend verification but email is already verified: ' . $request->user()->id);
            return redirect($this->redirectPath())->with('status', 'Email déjà vérifié.');
        }

        $request->user()->sendEmailVerificationNotification();
        Log::info('Verification email resent for user: ' . $request->user()->id);

        return back()->with('resent', true);
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


