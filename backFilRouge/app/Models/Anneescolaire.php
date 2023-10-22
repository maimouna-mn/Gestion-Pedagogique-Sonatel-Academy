<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anneescolaire extends Model
{
    use HasFactory;
    public function anneeClasses() {
        return $this->hasMany(anneeClasse::class, 'anneescolaire_id');
    }
}
