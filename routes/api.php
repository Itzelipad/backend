<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReceptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/mostrar-recepciones',[ReceptionController::class,'show']);
    Route::get('/mostrar-recepcion/{id}',[ReceptionController::class,'showOne']);
    
});

Route::middleware(['auth:sanctum', 'role:1'])->group(function () {
    Route::post('/registrar-recepcion', [ReceptionController::class, 'register']);
    Route::put('/actualizar-recepcion/{id}',[ReceptionController::class,'edit']);
});

Route::middleware(['auth:sanctum', 'role:0'])->group(function () {

});
