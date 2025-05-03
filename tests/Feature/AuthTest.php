<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    

    public function test_admin_can_login()
    {

        // Run the seeder to create the admin
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@accelerate.com',
            'password' => 'accelerate@2025!?229',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'role'],
                     'token',
                 ])
                 ->assertJsonFragment(['email' => 'admin@accelerate.com', 'role' => 'admin']);
    }

    public function test_invalid_credentials_return_401()
    {
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@accelerate.com',
            'password' => 'wrongpassword1246',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'identifiants incorrects']);
    }

    public function test_admin_seeder_creates_admin()
    {
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@accelerate.com',
            'role' => 'admin',
        ]);
    }
    

}