<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{

    //tested
    public function register(Request $request)
    {
        try {
            $request->validate([
                'numero' => 'required|string|size:3',
                'edificio' => 'required|string|max:30'
            ]);
            Reception::create([
                'numero' => $request->numero,
                'edificio' => $request->edificio
            ]);
            return response()->json(['message' => 'Registro exitoso'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tetsted
    public function show()
    {
        $receptions = Reception::all();
        return response()->json([
            'recepciones' => $receptions
        ], 200);
    }

    //tested
    public function showOne($id)
    {
        $reception = Reception::find($id);
        if (!$reception)
            return response()->json(['message' => 'Recepcion no encontrada.'], 404);
        return response()->json([
            'recepcion' => $reception
        ], 200);
    }

    //tested
    public function update(Request $request, $id)
    {
        try {

            $reception = Reception::find($id);
            if (!$reception)
                return response()->json([
                    'message' => 'Recepcion no encontrada.'
                ], 404);

            $request->validate([
                'numero' => 'required|string|size:3',
                'edificio' => 'required|string|max:30'
            ]);

            $reception->update([
                'numero' => $request->numero,
                'edificio' => $request->edificio
            ]);

            return response()->json([
                'message' => 'Información actualizada exitosamente'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function showAvailable()
    {
        $receptions = Reception::whereNotIn(
            'id',
            User::whereNotNull('reception_id')->pluck('reception_id')
        )->get();
        return response()->json([
            'recepciones' => $receptions
        ]);
    }
}
