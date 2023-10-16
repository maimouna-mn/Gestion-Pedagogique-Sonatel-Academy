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
        return $etudiants;
    }



    // {
    //     "etudiants": [
    //         {
    //             "nom": "Étudiant 1",
    //             "email": "etudiant1@example.com"
    //         },
    //         {
    //             "nom": "Étudiant 2",
    //             "email": "etudiant2@example.com"
    //         },
    //         {
    //             "nom": "Étudiant 3",
    //             "email": "etudiant3@example.com"
    //         }
    //     ]
    // }


    public function store(Request $request)
    {
        $etudiants = $request->etudiants;

        $etudiantsData = [];

        foreach ($etudiants as $etudiant) {
            $etudiantsData[] = [
                'name' => $etudiant['name'],
                'email' => $etudiant['email'],
                'password' => $etudiant['password'],
                'role' => $etudiant['role']
            ];
        }

        DB::beginTransaction();

        try {
            User::insert($etudiantsData);
          $classe = anneeClasse::where('classe_id', $request->classe_id)->first();

            foreach ($etudiantsData as $etudiantData) {
                $inscriptionData = [
                    'annee_classe_id' => $classe->id,
                    'user_id' => User::where('email', $etudiantData['email'])->first()->id
                ];
                Inscriptions::create($inscriptionData);
            }

            DB::commit();

            return response('Étudiants ajoutés avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return response('Erreur lors de l\'ajout des étudiants', 500);
        }
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
}