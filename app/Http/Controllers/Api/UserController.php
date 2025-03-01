<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les utilisateurs
        $users = User::paginate(10);
        // On retourne les informations des utilisateurs en JSON
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
       $validatedData = $request->validated();

       // Hasher le mot de passe 
       $validatedData['password'] = Hash::make($validatedData['password']);

       $user = User::create($validatedData);

        // Générer le token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourner la réponse JSON avec un statut HTTP 201 (Créé)
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Utilisateur créé avec succès !'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, $id)
    {
        // On retourne les informations de l'utilisateur en JSON
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserStoreRequest $request, User $user)
    {
        // On modifie les informations de l'utilisateur
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        // On retourne la réponse JSON
        return response()->json();
    }

    public function destroy(User $user)
    {
        $user->delete();
        // On retourne la réponse JSON
        return response()->json();
    }

}
