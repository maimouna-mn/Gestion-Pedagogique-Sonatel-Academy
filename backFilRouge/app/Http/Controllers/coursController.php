<?php
namespace App\Http\Controllers;

use App\Http\Resources\coursResource;
use App\Models\Cours;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class coursController extends Controller
{
    public function all()
    {
        $cours = Cours::with('moduleProf')->get();
        return coursResource::collection($cours);
    }
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $cours = Cours::create([
                "semestre_id" => $request->semestre_id,
                "prof_module_id"=>$request->prof_module_id
            ]);

            $cours->classes()->attach($request->classes);
            return response($cours, Response::HTTP_CREATED);
        });

    }


}
