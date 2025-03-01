<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use Illuminate\Http\Request;

use App\Http\Resources\TaskResource as TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupère les tâches de l'utilisateur authentifié
        $query = Task::where('user_id', $request->user()->id);

        // Appliquer les filtres
        $this->applyFilters($query, $request);

        // Trier les tâches par date de création (les plus récentes en premier)
        $query->orderBy('created_at', 'desc');

        // Retourner les tâches paginées pour de meilleures performances
        return response()->json(TaskResource::collection($query->paginate(10)));
    }

    private function applyFilters($query, Request $request)
    {
        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtrer par titre avec recherche
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', "%{$request->input('search')}%");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $validatedData = $request->validated();
        $task = $request->user()->tasks()->create($validatedData);
        // Retourner la réponse avec un message de succès
        return response()->json([
            'task' => $task,
            'message' => 'Tâche créée avec succès !'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 
                "Vous n'avez l'autorisation de voir cette tâche"], 
                403);
        }
        $task->update($request->all());
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskStoreRequest $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => "Vous n'avez l'autorisation de modifier cette tâche"], 403);
        }
        $task->update($request->validated());
        // Retourner la réponse JSON avec un message de succès
        return response()->json([
            'task' => $task,
            'message' => 'Tâche mise à jour avec succès !'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $task->delete();
        return response()->json(['message' => 'Tâche suppriméeavec succès']);
    }
     
}
