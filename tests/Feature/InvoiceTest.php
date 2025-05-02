<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_invoice()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/invoices', [
            'client_id' => $client->id,
            'issue_date' => '2025-05-01',
            'due_date' => '2025-05-15',
            'lines' => [
                ['description' => 'Service A', 'amount' => 100.00],
                ['description' => 'Service B', 'amount' => 200.00],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'client', 'invoice_number', 'total_ht', 'lines']])
                 ->assertJsonFragment(['total_ht' => 300.00]);
    }

    public function test_admin_can_generate_pdf()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);
        Sanctum::actingAs($admin);

        $response = $this->get('/api/v1/invoices/' . $invoice->id . '/pdf');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }
}