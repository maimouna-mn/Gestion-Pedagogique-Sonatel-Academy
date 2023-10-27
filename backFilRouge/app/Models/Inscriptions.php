<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscriptions extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classe()
    {
        return $this->belongsTo(anneeClasse::class, 'annne_classe_id');
    }
}
