<?php

namespace App\Models;

use App\Events\SessionEnCours;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Session extends Model
{
    protected $dispatchesEvents = [
        'created' => SessionEnCours::class,
    ];
    protected $guarded=[];

    use HasFactory;
   
    
    public function coursClasses() {
        return $this->belongsToMany(coursClasse::class, 'session_cours_classes');
    }
    public function sessionClasseCours(): BelongsToMany
{
    return $this->belongsToMany(coursClasse::class, 'session_cours_classes');
}

}
