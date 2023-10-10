<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class sessionController extends Controller
{
    public function store(Request $request){
        $validatedData = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'Type' => 'required|in:presentiel,enligne',
            'salle_id' => $request->Type == 'presentiel' ? 'required|exists:salles,id' : 'nullable',
        ]);

        if ($validatedData['Type'] == 'presentiel') {
            $existingSession = Session::where('date', $validatedData['date'])
                ->where('salle_id', $validatedData['salle_id'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('heure_debut', [$validatedData['heure_debut'], $validatedData['heure_fin']])
                    ->orWhereBetween('heure_debut',[$validatedData['heure_debut'],$validatedData['heure_fin']]);
                })
                ->first();

            if ($existingSession) {
                //session existe pour salle, date et heure
                return response()->json(['error' => 'Une session existe déjà pour cette salle, cette date et cette heure .'], Response::HTTP_CONFLICT);
            }
        }

        return DB::transaction(function () use ($validatedData,$request) {
            $session = Session::create($validatedData);
            $session->sessionClasseCours()->attach($request->sessionClasseCours);
            return response($session, Response::HTTP_CREATED);
        });
    }



}
