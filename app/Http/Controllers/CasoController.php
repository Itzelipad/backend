<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CasoController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'desglose' => 'required|string|max:255',
                'cantidad' => 'required|int',
                'id_doctor' => 'required|exists:doctors,id',
                'id_reception' => 'required|exists:receptions,id'
            ]);
            $time = Carbon::now('America/Mexico_City');
            $hora = $time->toTimeString();
            $fecha = $time->toDateString();
            Caso::create([
                'hora' => $hora,
                'fecha' => $fecha,
                'desglose' => $request->desglose,
                'cantidad' => $request->cantidad,
                'id_doctor' => $request->id_doctor,
                'id_reception' => $request->id_reception,
            ]);
            return response()->json([
                'message' => 'El caso se ha registrado con éxito.'
            ],200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
