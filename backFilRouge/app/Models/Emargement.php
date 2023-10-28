<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
    use HasFactory;
    protected $fillable = ['presence', 'inscriptions_id', 'session_cours_classe_id'];

    public function inscription()
    {
        return $this->belongsTo(Inscriptions::class, 'inscriptions_id');
    }

    public function sessionCoursClasse()
    {
        return $this->belongsTo(sessionCoursClasse::class, 'session_cours_classe_id');
    }
}
