<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anneeClasse extends Model
{
    use HasFactory;
    
    public function classe() {
        return $this->belongsTo(Classe::class);
    }
    public function coursClasses() {
        return $this->hasMany(coursClasse::class, 'annee_classe_id', 'id');
    }
    public function cours() {
        return $this->hasMany(coursClasse::class, 'annee_classe_id', 'id')->with('cours');
    }
}
