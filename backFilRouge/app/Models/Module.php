<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    public function profModules()
    {
        return $this->hasMany(profModule::class, 'user_id');
    }
    public function professeurs()
    {
        return $this->belongsToMany(User::class, 'prof_modules','module_id','user_id')
        ->withPivot('id');
    }
    public function courses()
    {
        return $this->hasMany(Cours::class);
    }
}
