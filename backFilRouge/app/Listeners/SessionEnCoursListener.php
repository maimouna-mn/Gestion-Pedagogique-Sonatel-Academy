<?php

namespace App\Listeners;

use App\Events\SessionEnCours;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;

class SessionEnCoursListener
{
    /**
     * Create the event listener.
     */
  
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   
    public function handle(SessionEnCours $event)
    {
        $session = $event->session;
        // $now = Carbon::now()->format('Y-m-d H:i');
        // $sessionStart = $session->date . ' ' . $session->heure_debut;

        // if ($now >= $sessionStart) {
        //     $session->status = 'en_cours';
        // }
        // $session->status = 'en_cours';
        //     $session->save();
       $session->update(['status' => 'en_cours']);

        
    }
}
