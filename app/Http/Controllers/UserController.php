<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        \DB::beginTransaction();
        try {
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'second_last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ], [
                'required' => 'Todos los campos son requeridos'
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente'
            ]);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
        
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
    
        return response()->json(['message' => 'Logout completado']);
    }
}
