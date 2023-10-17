<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRequest;
use App\Imports\UserImport;
use App\Models\anneeClasse;
use App\Models\Classe;
use App\Models\Inscriptions;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Cookie;

class UserController extends Controller
{
    public function all()
    {
        $etudiants = User::where('role', 'etudiant')->get();

        $classes = Classe::all();
        return [
            "data" => $etudiants,
            "data1" => $classes
        ];
    }
   


    // public function store(Request $request)
    // {
    
    //     $file = $request->file('excel_file');
    
    //     $fileType = 'Csv';
    //     Excel::import(new UserImport, $file, $fileType);
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

            return response()->json('etudiants ajoutés avec succes');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('erreur lors de l\'ajout des etudiants');
        }
    }


    public function classeEleves($id)
    {
        $annee = anneeClasse::where('classe_id', $id)
            ->where("anneescolaire_id", 1)
            ->first();
        $eleves = Inscriptions::where("annee_classe_id", $annee->id)->get();
        return [
            "data1" => $annee,
            "data2" => $eleves,
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