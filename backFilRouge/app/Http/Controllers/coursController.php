<?php
namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class coursController extends Controller
{
    public function store(Request $request){
        $cours=Cours::create([
            "heures_global" => $request->heures_global,
            "professeur_id" => $request->professeur_id,
            "module_id" => $request->module_id,
            "semestre_id" => $request->semestre_id,
        ]);
        return response($cours, Response::HTTP_CREATED);
    }

}
