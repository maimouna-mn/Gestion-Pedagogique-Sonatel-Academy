<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class sessionCoursClasse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sessionClasse(): BelongsToMany
    {
        return $this->belongsToMany(coursClasse::class, 'session_cours_classes');
    }
    public function cours()
    {
        return $this->belongsTo(CoursClasse::class, 'cours_classe_id', 'id');
    }
    public function session()
    {
        return $this->belongsTo(Session::class)->onDelete('cascade');
    }

}