<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class coursResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $heuresGlobal = $this->coursClasses->map(function ($coursClass) {
            return $coursClass->heures_global;
        });
        $nombreHeureR = $this->coursClasses->map(function ($coursClass) {
            return $coursClass->nombreHeureR;
        });
        return [
            'id' => $this->id,
            'heures_global' =>$heuresGlobal,
            'nombreHeureR' =>$nombreHeureR,
            'semestre' => $this->semestre->libelle,
            'moduleProf' =>new profmoduleResource($this->moduleProf) ,
        ];
    }
}
