<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\PdfGenerationService;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\StoreInvoiceRequest;

/**
 * @OA\Tag(
 *     name="Invoices",
 *     description="API endpoints for managing invoices"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use a Sanctum token obtained from /api/v1/login"
 * )
 * @OA\Schema(
 *     schema="Invoice",
 *     type="object",
 *     required={"id", "client", "invoice_number", "total_ht", "issue_date", "due_date", "status", "lines"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="client", ref="#/components/schemas/Client"),
 *     @OA\Property(property="invoice_number", type="string", example="INV-2025-0001"),
 *     @OA\Property(property="total_ht", type="number", format="float", example=300.00),
 *     @OA\Property(property="issue_date", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-05-15"),
 *     @OA\Property(property="status", type="string", enum={"draft", "paid", "cancelled"}, example="draft"),
 *     @OA\Property(
 *         property="lines",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/InvoiceLine")
 *     )
 * )
 * @OA\Schema(
 *     schema="InvoiceLine",
 *     type="object",
 *     required={"id", "description", "amount"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="description", type="string", example="Service A"),
 *     @OA\Property(property="amount", type="number", format="float", example=100.00)
 * )
 */
 class InvoiceController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/invoices",
     *     tags={"Invoices"},
     *     summary="List all invoices",
     *     description="Retrieve a paginated list of invoices with optional filters. Restricted to admin users.",
     *     operationId="listInvoices",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="Filter by client ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter invoices issued on or after this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
            @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by invoice status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "paid", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of invoices",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Invoice")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'accéder à cette ressource")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'lines']);
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('date_from')) {
            $query->where('issue_date', '>=', $request->date_from);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return InvoiceResource::collection($query->paginate(10));
    }

     /**
     * @OA\Post(
     *     path="/api/v1/invoices",
     *     tags={"Invoices"},
     *     summary="Create a new invoice",
     *     description="Create a new invoice with associated lines. Restricted to admin users.",
     *     operationId="createInvoice",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="issue_date", type="string", format="date", example="2025-05-01"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-05-15"),
     *             @OA\Property(
     *                 property="lines",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="description", type="string", example="Service A"),
     *                     @OA\Property(property="amount", type="number", format="float", example=100.00)
     *                 )
     *             ),
     *             required={"client_id", "issue_date", "due_date", "lines"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Invoice created",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Invoice"),
     *             @OA\Property(property="message", type="string", example="Facture créée avec succès !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'effectuer cette action")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="client_id", type="array", @OA\Items(type="string", example="The selected client id is invalid."))
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreInvoiceRequest $request)
    {
        // Vérifier l'authentification et le rôle admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation d'effectuer cette action"
            ], 403);
        }

        try {
            return DB::transaction(function () use ($request) {
                // Créer la facture
                $invoice = Invoice::create([
                    'client_id' => $request->client_id,
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
                    'issue_date' => $request->issue_date,
                    'due_date' => $request->due_date,
                    'total_ht' => collect($request->lines)->sum('amount'),
                    'status' => 'draft',
                ]);

                // Créer les lignes de facture
                foreach ($request->lines as $line) {
                    InvoiceLine::create([
                        'invoice_id' => $invoice->id,
                        'description' => $line['description'],
                        'amount' => $line['amount'],
                    ]);
                }

                // Retourner une réponse standardisée
                return response()->json([
                    'data' => new InvoiceResource($invoice->load(['client', 'lines'])),
                    'message' => 'Facture créée avec succès !'
                ], 201);
            });
        } catch (Exception $e) {
            // Journaliser l'erreur
            Log::error('Erreur lors de la création de la facture : ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la facture.',
            ], 500);
        }
    }
    
 
    /**
     * @OA\Get(
     *     path="/api/v1/invoices/{id}",
     *     tags={"Invoices"},
     *     summary="Retrieve a specific invoice",
     *     description="Get details of a specific invoice. Restricted to admin users.",
     *     operationId="getInvoice",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Invoice ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Invoice"),
     *             @OA\Property(property="message", type="string", example="Facture récupérée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'accéder à cette facture")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Invoice] {id}")
     *         )
     *     )
     * )
     */
     public function show(Invoice $invoice)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation d'accéder à cette facture"
            ], 403);
        }

        try {
            return response()->json([
                'data' => new InvoiceResource($invoice->load(['client', 'lines'])),
                'message' => 'Facture récupérée avec succès'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la facture : ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération de la facture'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/invoices/{id}",
     *     tags={"Invoices"},
     *     summary="Delete a specific invoice",
     *     description="Delete a specific invoice if it is not paid. Restricted to admin users.",
     *     operationId="deleteInvoice",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Invoice ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1)
     *             ),
     *             @OA\Property(property="message", type="string", example="Facture supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'effectuer cette action")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Invoice] {id}")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invoice cannot be deleted (e.g., paid)",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Impossible de supprimer une facture déjà payée")
     *         )
     *     )
     * )
     */
    public function destroy(Invoice $invoice)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation d'effectuer cette action"
            ], 403);
        }

        if ($invoice->status === 'paid') {
            return response()->json([
                'message' => "Impossible de supprimer une facture déjà payée"
            ], 422);
        }

        try {
            DB::transaction(function () use ($invoice) {
                $invoice->lines()->delete();
                $invoice->delete();

                Log::info('Facture supprimée', [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'user_id' => Auth::id(),
                ]);
            });

            return response()->json([
                'data' => ['id' => $invoice->id],
                'message' => 'Facture supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la facture : ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression de la facture'
            ], 500);
        }
    }
    
    /**
     * @OA\Get(
     *     path="/api/v1/invoices/{id}/pdf",
     *     tags={"Invoices"},
     *     summary="Generate PDF for a specific invoice",
     *     description="Download a PDF version of a specific invoice. Restricted to admin users.",
     *     operationId="generateInvoicePdf",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Invoice ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF generated successfully",
     *         @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Non authentifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'accéder à cette facture")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Invoice] {id}")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Une erreur est survenue lors de la génération du PDF")
     *         )
     *     )
     * )
     */
    public function generatePdf(Invoice $invoice)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation d'accéder à cette facture"
            ], 403);
        }

        try {
            $pdf = Pdf::loadView('pdf.invoice', [
                'invoice' => $invoice->load(['client', 'lines'])
            ]);

            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la génération du PDF'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/history",
     *     tags={"Invoices"},
     *     summary="Retrieve invoice history",
     *     description="Retrieve a paginated list of all invoices with optional date range filters. Restricted to admin users.",
     *     operationId="getInvoiceHistory",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter invoices issued on or after this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter invoices issued on or before this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by invoice status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "paid", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice history retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Invoice")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Non authentifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation d'accéder à cette ressource")
     *         )
     *     )
     * )
     */
    public function history(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation d'accéder à cette ressource"
            ], 403);
        }

        $query = Invoice::with(['client', 'lines']);

        if ($request->has('start_date')) {
            $query->where('issue_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('issue_date', '<=', $request->end_date);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return InvoiceResource::collection($query->orderBy('issue_date', 'desc')->paginate(10));
    }
    
}
