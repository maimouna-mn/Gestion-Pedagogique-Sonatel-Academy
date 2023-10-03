<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class sessionController extends Controller
{
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $session = Session::create([
                "date" => $request->date,
                "heure_debut" => $request->heure_debut,
                "heure_fin" => $request->heure_fin,
                "Type" => $request->Type,
                "salle_id" => $request->salle_id,
                "cours_id" => $request->cours_id,
            ]);

            $session->sessionClasseCours()->attach($request->sessionClasseCours);

            return response($session, Response::HTTP_CREATED);
        });
    }

}
