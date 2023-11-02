<?php

namespace App\Http\Controllers;

use App\Http\Resources\sessionResource;
use App\Models\anneeClasse;
use App\Models\Annulation;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\coursClasse;
use App\Models\Emargement;
use App\Models\Inscriptions;
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



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'heure_debut' => ['required', 'date_format:H:i', 'after_or_equal:08:00', 'before_or_equal:23:00'],
            // L'heure de début doit être entre 08:00 et 16:00
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
            // L'heure de fin doit être postérieure à l'heure de début
            'Type' => ['required', 'in:presentiel,enLigne'],
            'salle_id' => $request->Type == 'presentiel' ? ['required', 'exists:salles,id'] : 'nullable',
            'status' => 'string',
        ], [
            'date.after_or_equal' => 'La date doit être supérieure ou égale à aujourd\'hui.',
            'heure_debut.after_or_equal' => 'L\'heure de début doit être entre 08:00 et 16:00.',
            'heure_debut.before_or_equal' => 'L\'heure de début doit être entre 08:00 et 16:00.',
            'heure_fin.after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
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
        } else if ($validatedData['Type'] == 'enLigne') {
            $existingSession = Session::where('date', $validatedData['date'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('heure_debut', [$validatedData['heure_debut'], $validatedData['heure_fin']])
                        ->orWhereBetween('heure_fin', [$validatedData['heure_debut'], $validatedData['heure_fin']]);
                })
                ->first();

            if ($existingSession) {
                return response()->json(['error' => 'Une session existe déjà pour  cette date et cette heure.'], 200);
            }
        }

        return DB::transaction(function () use ($validatedData, $request) {
            $session = Session::create([
                'date' => $validatedData['date'],
                'heure_debut' => $validatedData['heure_debut'],
                'heure_fin' => $validatedData['heure_fin'],
                'Type' => $validatedData['Type'],
                'salle_id' => $validatedData['salle_id'],
                'status' => 'en_attente',
            ]);

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
                $id = $coursClasse->id;
                $sessionId = sessionCoursClasse::where('session_id', $session->id)
                    ->where('cours_classe_id', $id)
                    ->first()->id;
                $classeId = $coursClasse->annee_classe_id;
                $classeEleves = $this->classeEleves($classeId);
                foreach ($classeEleves['data2'] as $eleve) {
                    $inscription_id = Inscriptions::where('user_id', $eleve->id)->first()->id;
                    Emargement::create([
                        'inscriptions_id' => $inscription_id,
                        'session_cours_classe_id' => $sessionId,
                        'presence' => 0,
                    ]);
                }
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
                            'session_cours_classe_id' => $session->id,
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
        $session->delete();
        return response()->json(['message' => 'Session et enregistrements associés supprimés avec succès']);

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

        if ($this->isSessionEnCours($id)) {
            return response()->json(["error" => "Impossible d'invalider une session en cours"]);
        }

        $session->status = 'invalidee';
        $session->save();
        return response()->json(['message' => 'Session invalidée avec succès.'], Response::HTTP_OK);

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

    public function sessionsEleve($eleveId)
    {
        $eleve = User::find($eleveId);

        if (!$eleve) {
            return response()->json(['message' => 'Élève non trouvé'], 404);
        }

        $inscriptions = Inscriptions::where('user_id', $eleveId)->get();


        foreach ($inscriptions as $inscription) {

            $classe = anneeClasse::where("id", $inscription->annee_classe_id)->first();
            $session = $this->sessionClasse($classe->classe_id);
        }

        return [
            "inscription_id" => $inscription->id,
            "eleve" => $eleve,
            "sessions" => $session,
        ];
    }



    public function emargement($inscriptionsId, $sessionCoursClasseId)
    {
        $emarge = Emargement::where('inscriptions_id', $inscriptionsId)
            ->where('session_cours_classe_id', $sessionCoursClasseId)
            ->where('presence', 0)
            ->first();

        if ($emarge) {
            $emarge->update(['presence' => 1]);
        } else {
            return response()->json(["error" => "deja emagé"]);
        }

        return $emarge;
    }


    public function elevesPresentAbsent($sessionCoursClasseId)
    {
        $emargements = Emargement::where('session_cours_classe_id', $sessionCoursClasseId)
            ->with('inscription')
            ->get();

        $result = [];

        foreach ($emargements as $emargement) {
            $inscription = Inscriptions::where('id', $emargement->inscriptions_id)->first();
            $user = User::find($inscription->user_id);

            $result[] = [
                'user' => $user,
                'presence' => $emargement->presence,
                'session_cours_classe_id' => $sessionCoursClasseId
            ];
        }

        return $result;
    }


    public function classeEleves($id)
    {

        $eleves = Inscriptions::where("annee_classe_id", $id)->get();
        $tab = [];
        foreach ($eleves as $eleve) {
            $user = User::find($eleve->user_id);
            $tab[] =
                $user
            ;
        }
        return [
            "data2" => $tab,
        ];
    }

}