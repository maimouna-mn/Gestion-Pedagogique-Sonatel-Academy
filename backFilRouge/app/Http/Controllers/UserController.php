<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRequest;
use App\Models\anneeClasse;
use App\Models\Classe;
use App\Models\Inscriptions;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Cookie;

class UserController extends Controller
{
    public function all()
    {
        $etudiants = User::where('role', 'etudiant')->get();

        $classes = Classe::orderBy('id', 'desc')->get();
        return [
            "data" => $etudiants,
            "data1" => $classes
        ];
    }

    // public function store(Request $request)
    // {
    //     $etudiants = $request->etudiants;

    //     $etudiantsData = [];

    //     foreach ($etudiants as $etudiant) {
    //         $hashedPassword = password_hash($etudiant['password'], PASSWORD_BCRYPT);

    //         $etudiantsData[] = [
    //             'name' => $etudiant['name'],
    //             'email' => $etudiant['email'],
    //             'password' => $hashedPassword,
    //             'role' => $etudiant['role']
    //         ];
    //     }

    //     DB::beginTransaction();

    //     try {
    //         User::insert($etudiantsData);
    //         $classe = anneeClasse::where('classe_id', $request->classe_id)->first();

    //         foreach ($etudiantsData as $etudiantData) {
    //             $inscriptionData = [
    //                 'annee_classe_id' => $classe->id,
    //                 'user_id' => User::where('email', $etudiantData['email'])->first()->id
    //             ];
    //             Inscriptions::create($inscriptionData);
    //         }
    //         $classeEf = Classe::find($request->classe_id);
    //         $nouvelEffectif = $classeEf->effectif + count($etudiantsData);
    //         $classeEf->update(['effectif' => $nouvelEffectif]);

    //         DB::commit();

    //         return response()->json([
    //             "message" => 'etudiants ajoutés avec succes',
    //             "data" => $classe
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(["error" => 'erreur lors de l\'ajout des etudiants']);
    //     }
    // }


    // public function store(Request $request)
    // {
    //     $etudiants = $request->etudiants;

    //     $etudiantsData = [];

    //     foreach ($etudiants as $etudiant) {
    //         $hashedPassword = password_hash($etudiant['password'], PASSWORD_BCRYPT);

    //         $etudiantsData[] = [
    //             'name' => $etudiant['name'],
    //             'email' => $etudiant['email'],
    //             'password' => $hashedPassword,
    //             'role' => $etudiant['role']
    //         ];
    //     }

    //     DB::beginTransaction();

    //     try {
    //         User::insert($etudiantsData);
    //         $classe_id = Classe::where('libelle', $request->libelle)->first()->id;
    //         $classe = anneeClasse::where('classe_id', $classe_id)->first();

    //         foreach ($etudiantsData as $etudiantData) {
    //             $inscriptionData = [
    //                 'annee_classe_id' => $classe->id,
    //                 'user_id' => User::where('email', $etudiantData['email'])->first()->id
    //             ];

    //             Inscriptions::create($inscriptionData);
    //         }

    //         $classeEf = Classe::find($classe_id);
    //         $nouvelEffectif = $classeEf->effectif + count($etudiantsData);
    //         $classeEf->update(['effectif' => $nouvelEffectif]);

    //         DB::commit();

    //         return response()->json([
    //             "message" => 'etudiants ajoutés avec succes',
    //             "data" => $classe
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(["error" => 'erreur lors de l\'ajout des etudiants']);
    //     }
    // }
    // public function store(Request $request)
    // {
    //     $classesData = $request->classes;

    //     DB::beginTransaction();

    //     try {
    //         foreach ($classesData as $classData) {
    //             $etudiants = $classData['etudiants'];
    //             $classeLibelle = $classData['libelle'];

    //             $etudiantsData = [];

    //             foreach ($etudiants as $etudiant) {
    //                 $hashedPassword = password_hash($etudiant['password'], PASSWORD_BCRYPT);

    //                 $etudiantsData[] = [
    //                     'name' => $etudiant['name'],
    //                     'email' => $etudiant['email'],
    //                     'password' => $hashedPassword,
    //                     'role' => $etudiant['role']
    //                 ];
    //             }

    //             User::insert($etudiantsData);

    //             $classe_id = Classe::where('libelle', $classeLibelle)->first()->id;
    //             $classe = anneeClasse::where('classe_id', $classe_id)->first();

    //             foreach ($etudiantsData as $etudiantData) {
    //                 $inscriptionData = [
    //                     'annee_classe_id' => $classe->id,
    //                     'user_id' => User::where('email', $etudiantData['email'])->first()->id
    //                 ];

    //                 Inscriptions::create($inscriptionData);
    //             }

    //             $classeEf = Classe::find($classe_id);
    //             $nouvelEffectif = $classeEf->effectif + count($etudiantsData);
    //             $classeEf->update(['effectif' => $nouvelEffectif]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             "message" => 'Étudiants ajoutés avec succès',
    //             "data" => $classesData
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(["error" => 'Erreur lors de l\'ajout des étudiants']);
    //     }
    // }
    public function store(Request $request)
    {
        $etudiants = $request->etudiants;

        DB::beginTransaction();
        $classes = [];
        try {
            foreach ($etudiants as $etudiant) {
                $hashedPassword = password_hash($etudiant['password'], PASSWORD_BCRYPT);

                $etudiantData = [
                    'name' => $etudiant['name'],
                    'email' => $etudiant['email'],
                    'password' => $hashedPassword,
                    'role' => 'etudiant'
                ];

                User::create($etudiantData);

                $classeLibelle = $etudiant['classe'];

                $classe_id = Classe::where('libelle', $classeLibelle)->first()->id;

                $classe = anneeClasse::where('classe_id', $classe_id)->first();
                if (!$classe) {
                    return response()->json("classe");
                }
                $inscriptionData = [
                    'annee_classe_id' => $classe->id,
                    'user_id' => User::where('email', $etudiant['email'])->first()->id,
                    // 'classe' => $classeLibelle
                ];

                Inscriptions::create($inscriptionData);

                $classeEf = Classe::find($classe_id);
                $nouvelEffectif = $classeEf->effectif + 1;
                $classeEf->update(['effectif' => $nouvelEffectif]);
                $classes[] = [$classeEf];
            }

            DB::commit();

            return response()->json([
                "message" => 'Étudiants ajoutés avec succès',
                "data" => $etudiants,
                "data1" => Classe::orderBy('id', 'desc')->get()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => 'Erreur lors de l\'ajout des étudiants']);
        }
    }


    // UPDATE `classes` SET `effectif` = 0;

    public function classeEleves($id)
    {
        $annee = anneeClasse::where('classe_id', $id)
            ->where("anneescolaire_id", 2)
            ->first();
        $eleves = Inscriptions::where("annee_classe_id", $annee->id)->get();
        $tab = [];
        foreach ($eleves as $eleve) {
            $user = User::find($eleve->user_id);
            $tab[] =
                $user
            ;
        }
        return [
            "data1" => $annee,
            "data2" => $tab,
        ];
    }




    public function register(userRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);
        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "message" => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);
        return response([
            "token" => $token,
            "user" => $user,
        ])->withCookie($cookie);
    }

    public function loginEleve(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "message" => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);
        $inscription = Inscriptions::where("user_id", $user->id)->first();
        return response([
            "token" => $token,
            "user" => $user,
            "inscription_id" => $inscription->id,
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        Cookie::forget("token");

        return response([
            "message" => "success"
        ]);
    }
    public function storeClasse(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'libelle' => 'required|max:255',
            'niveau' => 'required|max:255',
        ]);

        $classe = new Classe();
        $classe->libelle = $validatedData['libelle'];
        $classe->niveau = $validatedData['niveau'];
        $classe->effectif = 0;

        $classe->save();

        
        $anneeClasse = new anneeClasse();
        $anneeClasse->classe_id = $classe->id;
        $anneeClasse->anneescolaire_id = 2;

        $anneeClasse->save();

        // Retourner la classe et l'anneeClasse créées
        return response()->json($classe);
    }
}



