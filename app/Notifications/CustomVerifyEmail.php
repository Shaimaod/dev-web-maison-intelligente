<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        // Generate the correct verification URL
        $verificationUrl = $this->verificationUrl($notifiable);

        // Construct and send the verification email
        return (new MailMessage)
            ->subject('Confirmez votre adresse e-mail - Maison Connectée')
            ->view('emails.verify', [
                'verificationUrl' => $verificationUrl,
                'user' => $notifiable
            ]);
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
}
