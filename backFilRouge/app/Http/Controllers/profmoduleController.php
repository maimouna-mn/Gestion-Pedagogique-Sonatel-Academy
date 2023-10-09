<?php

namespace App\Http\Controllers;

use App\Http\Resources\profmoduleResource;
use App\Models\profModule;
use Illuminate\Http\Request;

class profmoduleController extends Controller
{
    public function all()
    {
        return profmoduleResource::collection(profModule::all()) ;
    }
}
