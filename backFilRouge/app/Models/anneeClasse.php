<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anneeClasse extends Model
{
    use HasFactory;
    
    public function classe() {
        return $this->belongsTo(classe::class);
    }
    public function coursClasses() {
        return $this->hasMany(coursClasse::class, 'annee_classe_id', 'id');
    }
    public function cours() {
        return $this->hasMany(coursClasse::class, 'annee_classe_id', 'id')->with('cours');
    }
    public function etudiants()
    {
        return $this->belongsToMany(User::class, 'inscriptions', 'annee_classe_id', 'user_id');
    }
    public function anneeScolaire() {
        return $this->belongsTo(Anneescolaire::class, 'anneescolaire_id');
    }
}
