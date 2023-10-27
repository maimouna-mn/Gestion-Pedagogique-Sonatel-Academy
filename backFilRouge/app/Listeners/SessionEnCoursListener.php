<?php

namespace App\Listeners;

use App\Events\SessionEnCours;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SessionEnCoursListener
{
    /**
     * Create the event listener.
     */
    protected $listen = [
        SessionEnCours::class => [
            SessionEnCoursListener::class,
        ],
    ];
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   
    public function handle(SessionEnCours $event)
    {
        // Logique pour mettre à jour le statut de la session
        $session = $event->session;
        $now = now();
        $sessionStart = $session->date . ' ' . $session->heure_debut;

        if ($now >= $sessionStart) {
            $session->status = 'en_cours';
            $session->save();
        }
    }
}
