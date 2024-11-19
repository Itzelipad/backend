<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{

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

    public function show()
    {
        $receptions = Reception::all();
        return response()->json([
            'receptions' => $receptions
        ], 200);
    }

    public function showOne($id)
    {
        $reception = Reception::find($id);
        if (!$reception)
            return response()->json(['message' => 'Recepcion no encontrada.'], 404);
        return response()->json([
            'receptions' => $reception
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        try {
            $request->validate([
                'numero' => 'required|string|size:3',
                'edificio' => 'required|string|max:30'
            ]);

            $reception = Reception::find($id);
            if (!$reception)
                return response()->json(['message' => 'Recepcion no encontrada.'], 404);

            $reception->update([
                'numero' => $request->numero,
                'edificio' => $request->edificio
            ]);

            return response()->json(['message' => 'Información actualizada exitosamente'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
