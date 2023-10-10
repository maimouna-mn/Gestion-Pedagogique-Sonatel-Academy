<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class coursClasse extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function sessionClasseCours(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'session_cours_classes','cours_classe_id', 'session_id');
    }
}
