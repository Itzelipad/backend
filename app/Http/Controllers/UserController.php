<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //tested
    public function register(Request $request)
    {
        try {
            $request->validate([
                'admin' => 'required|boolean',
                'name' => 'required|string|max:255',
                'usuario' => 'required|string|unique:users,usuario',
                'password' => 'required|string'
            ]);
            User::create([
                'admin' => $request->admin,
                'name' => $request->name,
                'usuario' => $request->usuario,
                'password' => $request->password
            ]);
            return response()->json([
                'message' => 'El usuario se registro exitosamente.'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'admin' => 'required|boolean'
            ]);
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }
            $user->update($request->only(['name', 'admin']));
            return response()->json([
                'message' => 'El usuario se actualizo exitosamente.'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function show()
    {
        $users = User::all();
        return response()->json([
            "usuarios" => $users
        ], 200);
    }

    //tested
    public function updatePassword($id, Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|confirmed|string'
            ]);
            $user = User::find($id);
            if (!$user)
                return response()->json([
                    'message' => 'El usuario especificado no se encuentra registrado.'
                ], 404);
            $user->update([
                'password' => $request->password
            ]);
            return response()->json([
                'message' => 'La contrasña se actualizo con éxito.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    //tested
    public function selectReception($id)
    {
        $reception = Reception::find($id);
        if (!$reception)
            return response()->json([
                'message' => 'Esta recepción no existe.'
            ], 404);
        $selected = User::where('reception_id', $id)->first();
        if ($selected)
            return response()->json([
                'message' => 'Esta recepción ya esta seleccionada.'
            ], 303);
        auth()->user()->update(['reception_id' => $id]);
        return response()->json([
            'message' => 'Se ha seleccionado correctamente la recepción.'
        ],200);
    }
}
