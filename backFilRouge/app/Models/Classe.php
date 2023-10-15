<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    public function coursClasses()
    {
        return $this->hasMany(coursClasse::class, 'annee_cours_id');
    }

    public function anneeClasses() {
        return $this->hasMany(anneeClasse::class);
    }

    public function cours() {
        return $this->belongsToMany(Cours::class, 'cours_classes', 'annee_classe_id', 'cours_id');
    }

}
