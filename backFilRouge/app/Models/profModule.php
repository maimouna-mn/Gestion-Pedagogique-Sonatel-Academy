<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profModule extends Model
{
    use HasFactory;

    public function modules()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function professeurs()
    {
        return $this->belongsTo(Professeur::class, 'professeur_id');
    }
}
