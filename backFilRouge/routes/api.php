<?php
use App\Http\Controllers\coursController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\profmoduleController;
use App\Http\Controllers\sessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cours',[coursController::class,"store"]);
Route::get('/cours',[coursController::class,"all"]);
Route::get('/profModule',[profmoduleController::class,"all"]);
Route::post('/session',[sessionController::class,"store"]);
Route::get('/module',[ModuleController::class,"all"]);
