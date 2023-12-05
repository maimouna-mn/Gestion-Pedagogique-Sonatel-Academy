<?php
namespace App\Http\Controllers;

use App\Http\Resources\CoursEleve;
use App\Http\Resources\coursEtat;
use App\Http\Resources\coursResource;
use App\Models\anneeClasse;
use App\Models\Annulation;
use App\Models\Cours;
use App\Models\Classe;
use App\Models\coursClasse;
use App\Models\Inscriptions;
use App\Models\Module;
use App\Models\Semestre;
use App\Models\sessionCoursClasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class coursController extends Controller
{
    public function all()
    {
        $cours = Cours::with('moduleProf')->orderBy('id', 'desc')->paginate(5);
        return coursResource::collection($cours);
    }
    public function allSemestre()
    {
        $semestre1 = Semestre::all();
        return $semestre1;
    }
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cours = Cours::create([
                "semestre_id" => $request->semestre_id,
                "prof_module_id" => $request->prof_module_id
            ]);
            if (!$request->classes) {
                return response()->json(['error' => 'choisissez une classe'], 200);
            }
            foreach ($request->classes as $class) {
                $classe = Classe::find($class['classe_id']);

                if ($classe) {
                    // $anneeClasse = AnneeClasse::where('classe_id', $class['classe_id'])->where('anneescolaire_id', 1)->first();
                    $anneeClasse = AnneeClasse::where('classe_id', $class['classe_id'])
                        ->whereHas('anneeScolaire', function ($query) {
                            $query->where('statut', 1);
                        })
                        ->first();

                    if ($anneeClasse) {
                        $cours->classes()->attach($anneeClasse->id, [
                            'heures_global' => $class['heures_global'],
                            'nombreHeureR' => $class['heures_global']
                        ]);
                        // $cours->classes()->attach($anneeClasse->id, [['heures_global' => $class['heures_global']],['nombreHeureR' => $class['heures_global']]]);
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

            return new coursResource($cours);
        });
    }
    public function destroy($id)
    {
        $cours = Cours::findOrFail($id);
        $cours->classes()->detach();
        // $cours->sessionCoursClasses()->delete();
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé avec succès'], 200);
    }

    public function filtreCours(Request $request, $id)
    {
        $coursS = Cours::where("semestre_id", $id)->with('moduleProf')->get();
        return CoursResource::collection($coursS);
    }

    public function filtreEtatCours(Request $request, $etat)
    {
        $coursS = coursClasse::where("Termine", $etat)->with('cours')->get();
        return coursEtat::collection($coursS);
    }

    // public function recherche(Request $request, $code)
    // {
    //     $cours = Cours::where("libelle", $code)->get();
    //     return coursResource::collection($cours);
    // }

    public function recherche(Request $request, $code)
    {
        $courses = Cours::whereHas('module', function ($query) use ($code) {
            $query->where('libelle', $code);
        })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return coursResource::collection($courses);
        // return coursResource::collection($cours);
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
                "heures_global" => $coursClasse->heures_global,
                "module" => $module->id,
            ];
        }
        return $data;
    }

    public function getCoursDetails($id)
    {
        $cours = Cours::with(['profModule', 'profModule.professeur', 'classes'])->find($id);

        if (!$cours) {
            return response()->json(['message' => 'Cours non trouvé'], 404);
        }

        $coursDetails[] = [
            'Module' => $cours->profModule->module->libelle,
            'Professeur' => $cours->profModule->professeurs->name,
            'photo' => $cours->profModule->professeurs->photo,
        ];

        $classesDetails = [];
        // return $cours;

        foreach ($cours->classes as $classe) {
            $heures = $classe->pivot->heures_global;
            $heuresR = $classe->pivot->nombreHeureR;

            $classesDetails[] = [
                'Classe' => $classe->libelle,
                'Heures' => $heures,
                'HeuresR' => $heuresR,
            ];
        }

        return response()->json(["data1" => $classesDetails, "data2" => $coursDetails]);
    }

    public function coursesByProfessor($id)
    {
        $courses = Cours::with('moduleProf')
            ->whereHas('moduleProf', function ($query) use ($id) {
                // $query->where('user_id', $id)->where("role", "professeur");
                $query->where('user_id', $id);
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        // return $courses;
        return coursResource::collection($courses);
    }

    public function coursEtudiant($id)
    {
        $eleve = Inscriptions::where('user_id', $id)->first();
        $cours = anneeClasse::where('id', $eleve->annee_classe_id)->first();
        $cour1 = coursClasse::where('annee_classe_id', $cours->id)->get();
        // return $cour1;
        return CoursEleve::collection($cour1);
    }

    public function demandesEnAttente()
    {
        $demandesEnAttente = Annulation::where('statut', 'En attente')->paginate(8);
        $result = [];

        foreach ($demandesEnAttente as $demande) {
            $sessionCoursClasse = SessionCoursClasse::find($demande->session_cours_classe_id);
            $cours = $sessionCoursClasse->cours;

            $coursDetailsResponse = $this->getCoursDetails($cours->cours_id);
            $coursDetails = json_decode($coursDetailsResponse->getContent(), true);

            $result[] = [
                'module' => $coursDetails['data2'],
                'classe' => $coursDetails['data1'],
                'motif' => $demande->motif,
                'session_cours_classe_id' => $demande->session_cours_classe_id,
            ];
        }

        return response()->json(['data' => $result]);
    }


}