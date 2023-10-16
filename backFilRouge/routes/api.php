<?php
use App\Http\Controllers\coursController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\profmoduleController;
use App\Http\Controllers\semestreController;
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


Route::post('/login', [UserController::class, 'login']);
Route::post('/store', [UserController::class, 'store']);
Route::get('/all', [UserController::class, 'all']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
