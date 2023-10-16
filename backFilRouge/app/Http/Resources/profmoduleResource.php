<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class profmoduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id'=>$this->id,
            // 'module_id'=>$this->modules->id,
            'module'=>$this->modules->libelle,
            // 'professeur_id'=>$this->professeurs->id,
            'professeur'=>$this->professeurs->name,
        ];
    }
}
