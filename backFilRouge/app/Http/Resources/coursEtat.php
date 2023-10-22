<?php

namespace App\Http\Resources;

use App\Models\profModule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class coursEtat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'heures_global' => $this->heures_global,
            // 'id' => $this->cours->id,
            'semestre_id' => $this->cours->semestre->libelle,
            'moduleProf' => new profmoduleResource(profModule::find($this->cours->prof_module_id))

        ];

    }

}