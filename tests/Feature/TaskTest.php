<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{

    use RefreshDatabase;
    private $user;
    private $token;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crée un utilisateur et récupère le token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

    }

    /** @test */
    public function user_can_create_a_task()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Nouvelle tâche du jour',
            'description' => 'Ceci est une tâche test.',
            'status' => 'en attente'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

       $response->assertStatus(201)
         ->assertJson([
             'message' => 'Tâche créée avec succès !',
             'task' => [
                 'title' => 'Nouvelle tâche du jour',
                 'description' => 'Ceci est une tâche test.',
                 'status' => 'en attente',
             ]
         ]);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create([
            'title' => 'Ancien titre',
            'status' => 'en attente',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Nouveau titre', 
            'description' => 'Nouvelle description',
            'status' => 'en cours',  
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'task' => [
                        'title' => 'Nouveau titre',
                        'status' => 'en cours',
                    ],
                ]);
    }


    /** @test */
    public function user_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tâche supprimée avec succès !']);
    }

    public function test_unauthorized_access_for_task_creation()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Nouvelle tâche',
            'description' => 'Ceci est une tâche test.',
            'status' => 'en attente'
        ]);

        $response->assertStatus(401); 
    }

    public function test_task_creation_validation()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '',  
            'description' => 'Description de la tâche',
            'due_date' => '2025-12-31',
            'status' => 'en attente', // Statut valide
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
        
    }

    public function test_delete_non_existent_task()
    {
        $response = $this->deleteJson("/api/tasks/99", [], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404);  
    }

}
