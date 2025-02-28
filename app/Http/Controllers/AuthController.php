<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validated = $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json(
           [
                    'user' => $user,
                    'token' => $user->createToken('token')->plainTextToken
                ]);
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

            /*$request->validate([
            'email'  => 'required|email',
            'password'  => 'required|password',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['L\'email incorrect'],
                ]);
            }

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken
                ]);*/


    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
