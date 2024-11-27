<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    //users
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    //Auth
    Route::delete('/logout', [AuthController::class, 'logout']);

    //doctors
    Route::get('/mostrar-doctor/{id}',[DoctorController::class,'doctorReception']);
    
});

Route::middleware(['auth:sanctum', 'role:1'])->group(function () {
    
    //receptions
    Route::post('/registrar-recepcion', [ReceptionController::class, 'register']);
    Route::get('/mostrar-recepciones',[ReceptionController::class,'show']);
    Route::get('/mostrar-recepcion/{id}',[ReceptionController::class,'showOne']);
    Route::patch('/actualizar-recepcion/{id}',[ReceptionController::class,'update']);
    
    //doctors
    Route::post('/registrar-doctor',[DoctorController::class,'register']);
    Route::put('/actualizar-doctor/{id}',[DoctorController::class,'update']);
    Route::get('/mostrar-doctores',[DoctorController::class,'show']);
    
    //users
    Route::post('/registrar-usuario',[UserController::class,'register']);
    Route::patch('/actualizar-usuario/{id}',[UserController::class,'update']);
    Route::get('/usuarios',[UserController::class,'show']);
    Route::patch('/actualizar-contraseña/{id}',[UserController::class,'updatePassword']);
});

Route::middleware(['auth:sanctum', 'role:0'])->group(function () {
    
    //casos
    Route::post('/registrar-caso',[CasoController::class,'register']);

    //users
    Route::patch('/seleccionar-recepcion/{id}',[UserController::class,'selectReception']);

    //receptions
    Route::get('/recepciones-disponibles',[ReceptionController::class,'showAvailable']);
});
