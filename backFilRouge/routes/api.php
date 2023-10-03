<?php

use App\Http\Controllers\coursController;
use App\Http\Controllers\sessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cours',[coursController::class,"store"]);
Route::post('/session',[sessionController::class,"store"]);
