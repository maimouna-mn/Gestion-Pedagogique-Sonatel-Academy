<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;
    public function profModules()
    {
        return $this->hasMany(profModule::class, 'module_id');
    }
}
