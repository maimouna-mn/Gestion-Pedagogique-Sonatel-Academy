<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    public function profModules()
    {
        return $this->hasMany(profModule::class, 'professeur_id');
    }
    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'prof_modules','module_id','professeur_id')
        ->withPivot('id');
    }
}
