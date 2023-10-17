<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cours extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'cours_classes', 'cours_id', 'annee_classe_id')
            ->withPivot("heures_global");
    }
    

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'prof_modules');
    }
    public function moduleProf()
    {
        return $this->belongsTo(profModule::class, 'prof_module_id');
    }
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
    public function coursClasses()
    {
        return $this->hasMany(coursClasse::class, 'cours_id', 'id');

    }

    // public function classes()
    // {
    //     return $this->belongsToMany(anneeClasse::class, 'class_cours');
    // }

    public function anneeClasses()
    {
        return $this->belongsToMany(anneeClasse::class, 'annee_classes');
    }

  

    public function profModule()
    {
        return $this->belongsTo(profModule::class);
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    // public function session1(): BelongsToMany
    // {
    //     return $this->belongsToMany(Session::class, 'session_cours_classes');
    // }

}