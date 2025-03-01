<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

       // Hasher le mot de passe 
       $validatedData['password'] = Hash::make($validatedData['password']);

       $user = User::create($validatedData);

        // Générer le token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourner la réponse JSON avec un statut 
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Utilisateur créé avec succès !'
        ], 201);
        
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                ['message' => 'Informations incorrectes'], 
                401);
        }

            return response()->json([

                        'token' => $request->user()->createToken('token')->plainTextToken
            ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
