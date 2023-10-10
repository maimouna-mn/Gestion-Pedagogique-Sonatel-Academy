<?php
namespace App\Http\Controllers;

use App\Http\Resources\coursResource;
use App\Models\anneeClasse;
use App\Models\Cours;
use App\Models\Classe;
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
                "prof_module_id" => $request->prof_module_id,
                "heures_global" => $request->heures_global
            ]);

            foreach ($request->classes as $class) {
                $classe = Classe::find($class['classe_id']);

                if ($classe) {
                    $anneeClasse = AnneeClasse::where('classe_id', $class['classe_id'])->where('anneescolaire_id',1)->first();

                    if ($anneeClasse) {
                        $cours->classes()->attach($classe, ['annee_classe_id' => $anneeClasse->id]);
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
            $cours->semestre_id = $request->input('semestre_id');
            $cours->prof_module_id = $request->input('prof_module_id');
            $cours->heures_global = $request->input('heures_global');

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

    public function filtreCours(Request $request,$id){
    $coursS=Cours::where("semestre_id",$id)->with('moduleProf')->get();

    return CoursResource::collection($coursS);
   }
   public function recherche(Request $request,$code){
              $cours=Cours::where("libelle",$code)->get();
              return coursResource::collection($cours);
   }

}
