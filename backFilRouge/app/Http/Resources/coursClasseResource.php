<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class coursClasseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'cours'=> new coursResource($this->cours),
            // 'cours'=> $this->cours,
            'sessionCours'=>$this->sessionClasseCours
        ];
    }
}
