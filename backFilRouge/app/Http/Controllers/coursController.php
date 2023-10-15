<?php
namespace App\Http\Controllers;

use App\Http\Resources\coursResource;
use App\Models\anneeClasse;
use App\Models\Cours;
use App\Models\Classe;
use App\Models\coursClasse;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class coursController extends Controller
{
    public function all()
    {
        $cours = Cours::with('moduleProf')->orderBy('id', 'desc')->paginate(5);
        return coursResource::collection($cours);
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cours = Cours::create([
                "semestre_id" => $request->semestre_id,
                "prof_module_id" => $request->prof_module_id
                // "heures_global" => $request->heures_global
            ]);

            foreach ($request->classes as $class) {
                $classe = Classe::find($class['classe_id']);

                if ($classe) {
                    $anneeClasse = AnneeClasse::where('classe_id', $class['classe_id'])->where('anneescolaire_id', 1)->first();

                    if ($anneeClasse) {
                        // $cours->classes()->attach(['annee_classe_id'=> $anneeClasse->id,"heures_global" => $class['heures_global']]);
                        $cours->classes()->attach([$anneeClasse->id => ['heures_global' => $class['heures_global']]]);

                    }
                }
            }
            return new coursResource($cours);
        });
    }

    public function update(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $cours = Cours::findOrFail($id);
            $cours->semestre_id = $request->semestre_id;
            $cours->prof_module_id = $request->prof_module_id;
            $cours->heures_global = $request->heures_global;

            $cours->save();
            $cours->classes()->sync($request->classes);
            // $cours->classes()->sync($request->input('classes'));

            return new CoursResource($cours);
        });
    }
   

    public function destroy($id)
    {
        $cours = Cours::findOrFail($id);
        $cours->classes()->detach();
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé avec succès'], 200);
    }

    public function filtreCours(Request $request, $id)
    {
        $coursS = Cours::where("semestre_id", $id)->with('moduleProf')->get();

        return CoursResource::collection($coursS);
    }

    public function recherche(Request $request, $code)
    {
        $cours = Cours::where("libelle", $code)->get();
        return coursResource::collection($cours);
    }

    public function coursByClasse($moduleId)
    {
        $classes = Classe::whereHas('anneeClasses', function ($query) use ($moduleId) {
            $query->whereHas('coursClasses.cours', function ($subQuery) use ($moduleId) {
                $subQuery->where('prof_module_id', $moduleId);
            });
        })->get();

        $data = [];

        foreach ($classes as $classe) {
            $anneeClasse = anneeClasse::where("classe_id", $classe->id)->first();
            $coursClasse = coursClasse::where("annee_classe_id", $anneeClasse->id)->first();

            $module = Module::find($moduleId);

            $data[] = [
                "classe_id" => $classe->id,
                "classe" => $classe->libelle,
                "cours_classe_id" => $coursClasse->id,
                "module" => $module->id,

            ];
        }

        return $data;
    }


}