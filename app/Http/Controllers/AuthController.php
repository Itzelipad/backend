<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'usuario' => 'required|string',
                'password' => 'required|string'
            ]);
            if (!Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada con éxito',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
