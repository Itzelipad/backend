<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    //tested
    public function register(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
                'vinculacion' => 'required|boolean',
                'reception_id' => 'required|exists:receptions,id',
            ]);
            Doctor::create([
                'nombre' => $request->nombre,
                'vinculacion' => $request->vinculacion,
                'reception_id' => $request->reception_id
            ]);
            return response()->json([
                'message' => 'doctor registrado con exito.'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
                'reception_id' => 'required|exists:receptions,id',
                'vinculacion' => 'required|boolean'
            ]);
            $doctor = Doctor::find($id);
            $doctor->update([
                'nombre' => $request->nombre,
                'reception_id' => $request->reception_id,
                'vinculacion' => $request->vinculacion
            ]);
            return response()->json([
                'message' => 'Doctor actualizado con exito.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function doctorReception($id)
    {
        $doctors = Doctor::where('reception_id', $id)->get();
        if ($doctors->isEmpty())
            return response()->json([
                'message' => 'No se encontraron doctores en esa recepcion.'
            ], 404);
        return response()->json([
            'doctores' => $doctors
        ], 200);
    }

    //tested
    public function show(){
        $doctors = Doctor::all();
        return response()->json([
            'doctores' => $doctors
        ]);
    }
}
