<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YourCustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Cette propriété contiendra l'utilisateur

    /**
     * Créer une nouvelle instance de message.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user; // On assigne l'utilisateur passé au constructeur
    }

    /**
     * Construire le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify') // Utilisez la vue pour l'e-mail
                    ->subject('Confirmez votre e-mail - Maison Connectée');
    }
}

