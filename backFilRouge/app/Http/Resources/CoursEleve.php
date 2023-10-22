<?php

namespace App\Http\Resources;

use App\Models\profModule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursEleve extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */public function toArray($request)
{
    return [
        'id' => $this->id,
        'heures_global' => $this->heures_global,
        'nombreHeureR' => $this->nombreHeureR,
        'Termine' => $this->Termine,
        'module' =>new profmoduleResource(profModule::find($this->cours->prof_module_id)) ,
    ];
}

}
