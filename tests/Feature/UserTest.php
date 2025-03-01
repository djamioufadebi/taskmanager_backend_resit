<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase; 
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jean Pierre',
            'email' => 'jeanpierre@gmail.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'token'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jeanpierre@gmail.com',
        ]);

    }


    /** @test */
    public function useer_can_login()
    {
        $user = User::factory()->create([
            'email' => 'user1@gmail.com',
            'password' => bcrypt('password12345678'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user1@gmail.com',
            'password' => 'password12345678',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
        // verifie que le token est valide et non null
        $token = $response->json('token');
        $this->assertNotNull($token);
    }

}
