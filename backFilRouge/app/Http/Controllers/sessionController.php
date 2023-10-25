<?php

namespace App\Http\Controllers;

use App\Events\SessionEnCours;
use App\Http\Resources\sessionResource;
use App\Models\anneeClasse;
use App\Models\Annulation;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\coursClasse;
use App\Models\Module;
use App\Models\profModule;
use App\Models\Salle;
use App\Models\Session;
use App\Models\sessionCoursClasse;
use App\Models\User;
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
            "data1" => Salle::all(),
            "data2" => coursClasse::all(),
            "data3" => Classe::all()
        ];
    }


    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'date' => 'required|date|after_or_equal:today',
    //         'heure_debut' => 'required',
    //         'heure_fin' => 'required|after:heure_debut',
    //         'Type' => 'required|in:presentiel,enLigne',
    //         'salle_id' => $request->Type == 'presentiel' ? 'required|exists:salles,id' : 'nullable',
    //     ]);

    //     if ($validatedData['Type'] == 'presentiel') {
    //         $existingSession = Session::where('date', $validatedData['date'])
    //             ->where('salle_id', $validatedData['salle_id'])
    //             ->where(function ($query) use ($validatedData) {
    //                 $query->whereBetween('heure_debut', [$validatedData['heure_debut'], $validatedData['heure_fin']])
    //                     ->orWhereBetween('heure_fin', [$validatedData['heure_debut'], $validatedData['heure_fin']]);
    //             })
    //             ->first();

    //         if ($existingSession) {
    //             return response()->json(['error' => 'Une session existe déjà pour cette salle, cette date et cette heure.'], 200);
    //         }
    //     }

    //     return DB::transaction(function () use ($validatedData, $request) {
    //         $session = Session::create($validatedData);

    //         $session->sessionClasseCours()->attach($request->sessionClasseCours);
    //         $heureDebut = $session->heure_debut;
    //         $heureFin = $session->heure_fin;
    //         $dateTimeDebut = DateTime::createFromFormat('H:i', $heureDebut);
    //         $dateTimeFin = DateTime::createFromFormat('H:i', $heureFin);

    //         if ($dateTimeDebut && $dateTimeFin) {
    //             $interval = $dateTimeDebut->diff($dateTimeFin);
    //             $hours = $interval->h;
    //             $minutes = $interval->i;
    //             $totalDuration = $hours + ($minutes / 60);
    //         }

    //         foreach ($request->sessionClasseCours as $value) {
    //             $coursClasse = coursClasse::find($value['cours_classe_id']);
    //             if ($coursClasse) {
    //                 if ($totalDuration > $coursClasse->heures_global) {
    //                     return response()->json(['error' => 'heure de cours termine.'], 200);

    //                 }
    //                 $coursClasse->decrement('nombreHeureR', $totalDuration);
    //                 if ($coursClasse->nombreHeureR === 0) {
    //                     $coursClasse->update(['Termine' => true]);
    //                 }
    //             }
    //         }
    //         event(new SessionEnCours($session));
    //         return new sessionResource($session);
    //     });
    // }
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
                return response()->json(['error' => 'Une session existe déjà pour cette salle, cette date et cette heure.'], 200);
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
                    if ($totalDuration > $coursClasse->nombreHeureR) {
                        return response()->json(['error' => 'heure de cours termine.'], 200);
                    }
                    $coursClasse->decrement('nombreHeureR', $totalDuration);
                    if ($coursClasse->nombreHeureR === 0) {
                        $coursClasse->update(['Termine' => true]);
                    }
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
            return response()->json(['error' => 'Année de classe non trouvée'], 404);
        }

        $cours = coursClasse::where("annee_classe_id", $anneeClasse->id)->get();

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

    // public function sessionProfesseur($professeurId)
    // {
    //     $professeur = User::find($professeurId);

    //     if (!$professeur) {
    //         return response()->json(['message' => 'Professeur non trouvé'], 404);
    //     }

    //     $sessions = Session::whereHas('coursClasses', function ($query) use ($professeurId) {
    //         $query->whereHas('cours.profModule.professeurs', function ($q) use ($professeurId) {
    //             $q->where('id', $professeurId);
    //         });
    //     })->get();

    //     $formattedSessions = [];

    //     foreach ($sessions as $session) {
    //         $coursClasse = $session->coursClasses->first();

    //         $profModule = $coursClasse->cours->profModule;
    //         $module = $profModule->module;

    //         $formattedSessions[] = [
    //             'id' => $session->id,
    //             'date' => $session->date,
    //             'heure_debut' => $session->heure_debut,
    //             'statut' => $session->status,
    //             'heure_fin' => $session->heure_fin,
    //             'Type' => $session->Type,
    //             'salle_id' => Salle::find($session->salle_id),
    //             'module' => $module->libelle,
    //             'professeur' => $professeur->name,
    //         ];
    //     }

    //     return [
    //         "professeur" => $professeur,
    //         "sessions" => $formattedSessions,
    //     ];
    // }
    public function sessionProfesseur($professeurId)
    {
        $professeur = User::find($professeurId);

        if (!$professeur) {
            return response()->json(['message' => 'Professeur non trouvé'], 404);
        }

        $sessions = Session::whereHas('coursClasses', function ($query) use ($professeurId) {
            $query->whereHas('cours.profModule.professeurs', function ($q) use ($professeurId) {
                $q->where('id', $professeurId);
            });
        })->get();

        $formattedSessions = [];
        $classes = [];

        foreach ($sessions as $session) {
            $coursClasse = $session->coursClasses->first();

            if ($coursClasse) {
                $pivotData = $coursClasse->pivot;

                $id = sessionCoursClasse::where('session_id', $pivotData->session_id)->where("cours_classe_id", $pivotData->cours_classe_id)->first()->id;
            }


            $classe = $coursClasse->classe;

            $profModule = $coursClasse->cours->profModule;
            $module = $profModule->module;

            $formattedSessions[] = [
                'id' => $session->id,
                'session_cours_classe_id' => $id,
                'date' => $session->date,
                'heure_debut' => $session->heure_debut,
                'statut' => $session->status,
                'heure_fin' => $session->heure_fin,
                'Type' => $session->Type,
                'salle_id' => Salle::find($session->salle_id),
                'module' => $module->libelle,
                'professeur' => $professeur->name,
            ];

            if (!in_array($classe, $classes)) {
                $classes[] = $classe;
            }
        }

        return [
            "professeur" => $professeur,
            "sessions" => $formattedSessions,
            "classes" => $classes,
        ];
    }


    public function ModuleByClasse(Request $request, $classeId)
    {
        $classe = Classe::find($classeId);

        if (!$classe) {
            return response()->json(['error' => 'Classe non trouvée'], 404);
        }

        // $anneeClasse = AnneeClasse::where("classe_id", $classe->id)->where("anneescolaire_id", 3)->first();
        $anneeClasse = AnneeClasse::where("classe_id", $classe->id)->first();
        $coursClasse = CoursClasse::where('annee_classe_id', $anneeClasse->id)->get();
        $modules = [];

        foreach ($coursClasse as $coursClasseItem) {
            $cours = Cours::find($coursClasseItem->cours_id);
            $profModule = ProfModule::find($cours->prof_module_id);
            $module = Module::find($profModule->module_id);

            $modules[] = [
                'module' => $module,
                'cours_classe_id' => $coursClasseItem->id,
                'heures_global' => $coursClasseItem->heures_global,
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
    public function SupprimerSession(Request $request, $session_cours_classe_id)
    {
    $session = sessionCoursClasse::where('id', $session_cours_classe_id)->first();

        Annulation::where('session_cours_classe_id', $session_cours_classe_id)->delete();
        // if ($session) {
            $session->delete();

            return response()->json(['message' => 'Session et enregistrements associés supprimés avec succès']);
        // } else {
        //     return response()->json(['message' => 'Session non trouvée'], 404);
        // }
    }


    public function annulerSession($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['error' => 'Session introuvable.'], Response::HTTP_NOT_FOUND);
        }

        if ($this->isSessionEnCours($id)) {
            return response()->json(["error" => "impossible d'annuler"], 200);
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
            return response()->json(["error" => "impossible de valider"]);
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



    public function demandeAnnulation(Request $request, $session_cours_classe_id)
    {
        $request->validate([
            'motif' => 'required',
        ]);

        $annulation = Annulation::create([
            'motif' => $request->motif,
            'session_cours_classe_id' => $session_cours_classe_id,
        ]);

        return response()->json(['message' => 'Demande d\'annulation enregistrée avec succès', "data" => $annulation]);
    }




}