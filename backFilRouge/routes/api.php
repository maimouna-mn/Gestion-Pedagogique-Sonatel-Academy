<?php
use App\Http\Controllers\coursController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\profmoduleController;
use App\Http\Controllers\sessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::delete('/cours/{id}',[coursController::class,"destroy"]);
Route::get('/cours/filtre/{id}',[coursController::class,"filtreCours"]);
Route::get('/cours/recherche/{code}',[coursController::class,"filtreCours"]);
Route::post('/cours',[coursController::class,"store"]);
Route::get('/cours/all',[coursController::class,"allSemestre"]);
Route::get('/cours',[coursController::class,"all"]);
Route::get('/profModule',[profmoduleController::class,"all"]);
Route::post('/session',[sessionController::class,"store"]);
Route::get('/session',[sessionController::class,"all"]);
Route::get('/module',[ModuleController::class,"all"]);
Route::get('/cours/classes',[coursController::class,"listeClasses"]);
Route::get('/session/filtre/{classeId}',[sessionController::class,"sessionClasse"]);
Route::get('/session/filtre1/{classeId}',[sessionController::class,"ModuleByClasse"]);
Route::get('/cours/filtre1/{moduleId}',[coursController::class,"coursByClasse"]);
Route::get('/session/status/{session}',[sessionController::class,"isSessionEnCours"]);
Route::get('/session/annuler/{id}',[sessionController::class,"annulerSession"]);
Route::get('/session/valider/{id}',[sessionController::class,"validerSession"]);
Route::get('/session/invalider/{id}',[sessionController::class,"invaliderSession"]);
Route::get('/cours/getCoursDetails/{id}',[coursController::class,"getCoursDetails"]);
Route::get('/cours/coursprof/{id}',[coursController::class,"coursesByProfessor"]);
Route::get('/cours/filtreEtat/{etat}',[coursController::class,"filtreEtatCours"]);
//sessionsEleve($eleveId)
Route::get('/session/sessionsEleve/{eleveId}',[sessionController::class,"sessionsEleve"]);
Route::post('/login', [UserController::class, 'login']);
Route::post('/loginEleve', [UserController::class, 'loginEleve']);
Route::post('/store', [UserController::class, 'store']);
Route::get('/user/classeEleves/{id}', [UserController::class,'classeEleves']);
Route::get('/user', [UserController::class, 'all']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/cours/coursEtu/{id}',[coursController::class,"coursEtudiant"]);
// sessionProfesseur($professeurId)loginEleve
Route::get('/session/profSessions/{professeurId}',[sessionController::class,"sessionProfesseur"]);
Route::post('session/demandeAnnulation/{session_cours_classe_id}', [sessionController::class,"demandeAnnulation"]);
// SupprimerSession(Request $request,$session_cours_classe_id)
Route::delete('/session/supprimer/{session_cours_classe_id}',[sessionController::class,"SupprimerSession"]);
Route::get('/cours/demandesEnAttente',[coursController::class,"demandesEnAttente"]);
Route::get('session/emargement/{inscriptionsId}/{sessionCoursClasseId}', [sessionController::class, 'emargement']);
Route::get('session/present-absent/{sessionCoursClasseId}',[sessionController::class,'elevesPresentAbsent']);
Route::get('/session/classeEleves/{id}', [sessionController::class,'classeEleves']);
