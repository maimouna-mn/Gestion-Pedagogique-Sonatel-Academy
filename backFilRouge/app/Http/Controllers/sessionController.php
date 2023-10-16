<?php

namespace App\Http\Controllers;

use App\Events\SessionEnCours;
use App\Http\Resources\sessionResource;
use App\Models\anneeClasse;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\coursClasse;
use App\Models\Module;
use App\Models\profModule;
use App\Models\Salle;
use App\Models\Session;
use DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class sessionController extends Controller
{
    public function all()
    {
        return [
            // "data" => Session::all()->load('sessionClasseCours'),
            "data1" => Salle::all(),
            // "data2" => coursClasseResource::collection(coursClasse::all()),
            "data2" => coursClasse::all(),
            "data3" => Classe::all()
        ];

    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'Type' => 'required|in:presentiel,enLigne',
            'salle_id' => $request->Type == 'presentiel' ? 'required|exists:salles,id' : 'nullable',
        ]);
    
        if ($validatedData['Type'] == 'presentiel') {
            $existingSession = Session::where('date', $validatedData['date'])
                ->where('salle_id', $validatedData['salle_id'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('heure_debut', [$validatedData['heure_debut'], $validatedData['heure_fin']])
                        ->orWhereBetween('heure_fin', [$validatedData['heure_debut'], $validatedData['heure_fin']]);
                })
                ->first();
    
            if ($existingSession) {
                return response()->json(['error' => 'Une session existe déjà pour cette salle, cette date et cette heure.']);
            }
        }
    
        return DB::transaction(function () use ($validatedData, $request) {
            $session = Session::create($validatedData);
    
            $session->sessionClasseCours()->attach($request->sessionClasseCours);
            $heureDebut = $session->heure_debut;
            $heureFin = $session->heure_fin;
            $dateTimeDebut = DateTime::createFromFormat('H:i', $heureDebut);
            $dateTimeFin = DateTime::createFromFormat('H:i', $heureFin);
    
            if ($dateTimeDebut && $dateTimeFin) {
                $interval = $dateTimeDebut->diff($dateTimeFin);
                $hours = $interval->h;
                $minutes = $interval->i;
                $totalDuration = $hours + ($minutes / 60);
            }
    
            foreach ($request->sessionClasseCours as $value) {
                $coursClasse = coursClasse::find($value['cours_classe_id']);
                if ($coursClasse) {
                    if($totalDuration>$coursClasse->heures_global){
                            return "plus de duree";
                    }
                    $coursClasse->decrement('heures_global', $totalDuration);
                }
            }
    
            event(new SessionEnCours($session));
    
            return new sessionResource($session);
        });
    }

    public function sessionClasse($classeId)
    {
        $classe = Classe::find($classeId);

        if (!$classe) {
            return response()->json(['message' => 'Classe non trouvée'], 404);
        }

        $anneeClasse = anneeClasse::where("classe_id", $classeId)->first();

        if (!$anneeClasse) {
            return response()->json(['message' => 'Année de classe non trouvée'], 404);
        }

        $cours = coursClasse::where("annee_classe_id", $anneeClasse->id)->get();
        $sessionsDuCours = [];

        $formattedSessions = [];
        foreach ($cours as $coursClasse) {
            $sessionsDuCours1 = $coursClasse->sessions;

            if (!empty($sessionsDuCours1)) {

                foreach ($sessionsDuCours1 as $session) {
                    $sessionModel = Session::find($session->session_id);
                    $coursClasse = $sessionModel->coursClasses->first();

                    if ($coursClasse) {
                        $profModule = $coursClasse->cours->profModule;

                        $module = $profModule->module;

                        $formattedSessions[] = [
                            'id' => $sessionModel->id,
                            'date' => $sessionModel->date,
                            'heure_debut' => $sessionModel->heure_debut,
                            'statut' => $sessionModel->status,
                            'heure_fin' => $sessionModel->heure_fin,
                            'Type' => $sessionModel->Type,
                            'salle_id' => Salle::find($sessionModel->salle_id),
                            'module' => $module->libelle,
                            'professeur' => $profModule->professeurs->name,
                        ];
                    }
                }
            }
        }


        return [
            "data1" => $classe,
            "data2" => $formattedSessions,
        ];
    }


    public function ModuleByClasse(Request $request, $classeId)
    {
        $classe = Classe::find($classeId);

        if (!$classe) {
            return response()->json(['message' => 'Classe non trouvée'], 404);
        }

        $anneeClasse = AnneeClasse::where("classe_id", $classe->id)->where("anneescolaire_id", 1)->first();
        $coursClasse = CoursClasse::where('annee_classe_id', $anneeClasse->id)->get();
        $modules = [];

        foreach ($coursClasse as $coursClasseItem) {
            $cours = Cours::find($coursClasseItem->cours_id);
            $profModule = ProfModule::find($cours->prof_module_id);
            $module = Module::find($profModule->module_id);

            $modules[] = [
                'module' => $module,
                'cours_classe_id' => $coursClasseItem->id,
            ];
        }

        return $modules;
    }


    public function isSessionEnCours($session)
    {
        $session1 = Session::find($session);

        if (!$session1) {
            return false;
        }

        $date = $session1->date;
        $heureDebut = $session1->heure_debut;
        $heureFin = $session1->heure_fin;

        if (!$date || !$heureDebut || !$heureFin) {
            return false;
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $dateDebut = ("$date $heureDebut");
        $dateFin = ("$date $heureFin");
        if ($now >= $dateDebut && $now <= $dateFin) {
            return true; // La session est en cours
        } else {
            return false; // La session pas en cours

        }
    }


    public function annulerSession($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['error' => 'Session introuvable.'], Response::HTTP_NOT_FOUND);
        }

        if ($this->isSessionEnCours($id)) {
            return response()->json("impossible d'annuler");
        }

        $session->status = 'annulee';
        $session->save();

        return response()->json(['message' => 'Session annulée avec succès.'], Response::HTTP_OK);
    }

    public function validerSession($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['error' => 'Session introuvable.'], Response::HTTP_NOT_FOUND);
        }


        if ($this->isSessionEnCours($id)) {
            return response()->json("impossible de valider");
        }
        // if (!$this->isSessionEnCours($id)) {
        //     return response()->json(['error' => 'Impossible de valider cette session car elle n\'a jamais été encours.'], Response::HTTP_BAD_REQUEST);
        // }

        $session->status = 'validee';
        $session->save();

        return response()->json(['message' => 'Session validée avec succès.'], Response::HTTP_OK);

    }

    public function invaliderSession($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['error' => 'Session introuvable.'], Response::HTTP_NOT_FOUND);
        }

        $dateFin = $session->date_fin;

        if (now() >= $dateFin) {
            $session->status = 'invalidee';
            $session->save();

            return response()->json(['message' => 'Session invalidée avec succès.'], Response::HTTP_OK);
        } else {
            return response()->json("Impossible d'invalider une session en cours ou avant la fin de la session.");
        }
    }






}