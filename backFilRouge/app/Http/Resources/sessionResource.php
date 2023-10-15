<?php

namespace App\Http\Resources;

use App\Models\Salle;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class sessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'session_classe_cours_id'=>$this->id,
            // 'session' =>new eventResource (Session::find($this->session_id)),

            "date" => $this->date,
            "heure_debut" => $this->heure_debut,
            "heure_fin" => $this->heure_fin,
            "Type" => $this->Type,
            "salle"=>Salle::find($this->salle_id)
        ];
    }
}
