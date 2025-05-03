<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Laravel\Sanctum\Sanctum;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\InvoiceResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Summary of test_admin_can_create_invoice
     * @return void
     */
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

    /**
     * Summary of test_invoice_resource_formats_dates_correctly
     * @return void
     */
    public function test_invoice_resource_formats_dates_correctly()
    {
        $invoice = Invoice::factory()->create([
            'issue_date' => '2025-05-01',
            'due_date' => '2025-05-15',
        ]);

        $resource = new InvoiceResource($invoice);
        $data = $resource->toArray(request());

        $this->assertEquals('2025-05-01', $data['issue_date']);
        $this->assertEquals('2025-05-15', $data['due_date']);
    }


    /**
     * Summary of test_create_invoice_with_invalid_client_id
     * @return void
     */
    public function test_create_invoice_with_invalid_client_id()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/invoices', [
            'client_id' => 999, 
            'issue_date' => '2025-05-01',
            'due_date' => '2025-05-15',
            'lines' => [['description' => 'Service A', 'amount' => 100.00]],
        ]);

        $response->assertStatus(422);
    }

    /**
     * Summary of test_admin_can_view_invoice
     * @return void
     */
    public function test_admin_can_view_invoice()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $invoice = Invoice::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/v1/invoices/' . $invoice->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'client',
                        'invoice_number',
                        'total_ht',
                        'issue_date',
                        'due_date',
                        'lines',
                    ],
                    'message',
                ]);
    }

    /**
     * Summary of test_admin_can_delete_invoice
     * @return void
     */
    public function test_admin_can_delete_invoice()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $invoice = Invoice::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/v1/invoices/' . $invoice->id);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Facture supprimée avec succès',
                    'data' => ['id' => $invoice->id],
                ]);

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    /**
     * Summary of test_non_admin_cannot_delete_invoice
     * @return void
     */
    public function test_non_admin_cannot_delete_invoice()
    {
        $user = User::factory()->create(['role' => 'user']);
        $invoice = Invoice::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/invoices/' . $invoice->id);

        $response->assertStatus(403)
                ->assertJson(['message' => "Vous n'avez pas l'autorisation d'effectuer cette action"]);
    }

     /**
     * Summary of test_admin_can_generate_pdf
     * @return void
     */
    public function test_admin_can_generate_pdf()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);
        Sanctum::actingAs($admin);

        $response = $this->get('/api/v1/invoices/' . $invoice->id . '/pdf');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf')
                 ->assertHeader('Content-Disposition', 'attachment; filename="invoice-' . $invoice->invoice_number . '.pdf"');
    }


    /**
     * Summary of test_pdf_contains_status
     * @return void
     */
    public function test_pdf_contains_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $invoice = Invoice::factory()->create(['status' => 'draft']);
        Sanctum::actingAs($admin);

        $response = $this->get("/api/v1/invoices/{$invoice->id}/pdf");
        $response->assertStatus(200);

        $pdfPath = storage_path('app/test.pdf');
        file_put_contents($pdfPath, $response->getContent());
        $text = Pdf::getText($pdfPath);
        $this->assertStringContainsString('Statut : Brouillon', $text);
    }


    /**
     * Summary of test_generate_pdf_success
     * @return void
     */
    public function test_generate_pdf_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'status' => 'draft',
        ]);
        InvoiceLine::factory()->create(['invoice_id' => $invoice->id]);

        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/v1/invoices/{$invoice->id}/pdf");

        $response->assertStatus(200)
                ->assertHeader('Content-Type', 'application/pdf')
                ->assertHeader('Content-Disposition', 'attachment; filename="invoice-' . $invoice->invoice_number . '.pdf"');
    }

}