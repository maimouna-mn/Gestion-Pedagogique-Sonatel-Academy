<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Session extends Model
{
    protected $guarded=[];

    use HasFactory;


    public function sessionClasseCours():BelongsToMany{
        return $this->belongsToMany(coursClasse::class,'session_cours_classes');
        // ->withPivot(")
    }
}
