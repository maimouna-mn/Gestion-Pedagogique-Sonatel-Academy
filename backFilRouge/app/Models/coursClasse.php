<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class coursClasse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sessionClasseCours(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'session_cours_classes', 'cours_classe_id', 'session_id');
    }
    public function sessionCoursClasses()
{
    return $this->hasMany(sessionCoursClasse::class, 'cours_classe_id')->onDelete('cascade');
}

    public function classes()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
    public function coursClasses()
    {
        return $this->hasMany(CoursClasse::class, 'cours_id')->onDelete('cascade');
    }
    
    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function cour1()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }
    public function sessions()
    {
        return $this->hasMany(sessionCoursClasse::class, 'cours_classe_id', 'id');
    }
    public function anneeClasse()
    {
        return $this->belongsTo(AnneeClasse::class, 'annee_classe_id', 'id');
    }

  

}
