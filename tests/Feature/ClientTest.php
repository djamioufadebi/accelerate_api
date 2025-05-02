<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_client()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/clients', [
            'name' => 'Odilon FANOU',
            'email' => 'odilon@fanou.com',
            'phone' => '+2290167223409',
            'address' => '123 St Michel',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'name', 'email', 'phone', 'address']]);
    }

    public function test_unauthenticated_user_cannot_create_client()
    {
        $response = $this->postJson('/api/v1/clients', [
            'name' => 'Odilon FANOU',
            'email' => 'odilon@fanou.com',
        ]);

        $response->assertStatus(401);
    }
}