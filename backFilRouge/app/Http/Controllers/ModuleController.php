<?php

namespace App\Http\Controllers;

use App\Http\Resources\coursResource;
use App\Models\Cours;
use App\Models\Module;
use App\Models\Classe;
use App\Models\Salle;
use App\Models\Semestre;

class ModuleController extends Controller
{
    public function all() {

      return [
        "data1"=>Module::with(('professeurs'))->get(),
        "data2"=>Classe::all(),
        "data3"=>Semestre::all(),
        // $cours = Cours::with('moduleProf')->orderBy('id', 'desc')->paginate(5);
        "data4"=> coursResource::collection(Cours::with('moduleProf')->get())
      ];
    }
}
