<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cours extends Model
{
    protected $guarded=[];
    use HasFactory;
    public function classes():BelongsToMany{
        return $this->belongsToMany(Classe::class,'cours_classes','cours_id','classe_id')
        ->withPivot("heures_global");
    }

    public function moduleProf()
    {
        return $this->belongsTo(profModule::class, 'prof_module_id');
    }
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
  
}
