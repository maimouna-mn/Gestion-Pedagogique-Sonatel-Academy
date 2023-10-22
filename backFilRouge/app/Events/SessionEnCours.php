<?php

namespace App\Events;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionEnCours
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $sessionStart = $this->session->date . ' ' . $this->session->heure_debut;
        if ($now >= $sessionStart) {
            $this->session->status = 'en_cours';
            $this->session->save();
        }
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}