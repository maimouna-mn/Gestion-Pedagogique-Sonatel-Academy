<?php

namespace App\Http\Resources;

use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class eventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "date"=>$this->date,
            "heure_debut"=>$this->heure_debut,
            "heure_fin"=>$this->heure_fin,
            "Type"=>$this->Type,
            "salle_id"=>Salle::find($this->salle_id),
        ];
    }
}
