<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PdfGenerationService;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\StoreInvoiceRequest;


/**
 * @OA\Schema(
 *     schema="Invoice",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="client", ref="#/components/schemas/Client"),
 *     @OA\Property(property="invoice_number", type="string"),
 *     @OA\Property(property="total_ht", type="number"),
 *     @OA\Property(property="issue_date", type="string", format="date"),
 *     @OA\Property(property="due_date", type="string", format="date"),
 *     @OA\Property(property="lines", type="array", @OA\Items(
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="amount", type="number")
 *     )),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class InvoiceController extends Controller
{

     /**
     * @OA\Get(
     *     path="/api/v1/invoices",
     *     tags={"Invoices"},
     *     summary="List all invoices",
     *     security={{"Sanctum":{}}},
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="Filter by client ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter by issue date (from)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of invoices",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Invoice")
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
        return InvoiceResource::collection($query->paginate(10));
    }


    /**
     * @OA\Post(
     *     path="/api/v1/invoices",
     *     tags={"Invoices"},
     *     summary="Create a new invoice",
     *     security={{"Sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id","issue_date","due_date","lines"},
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="issue_date", type="string", format="date", example="2025-05-01"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-05-15"),
     *             @OA\Property(property="lines", type="array", @OA\Items(
     *                 @OA\Property(property="description", type="string", example="Service A"),
     *                 @OA\Property(property="amount", type="number", example=100.00)
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Invoice created",
     *         @OA\JsonContent(ref="#/components/schemas/Invoice")
     *     )
     * )
     */
    public function store(StoreInvoiceRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'total_ht' => collect($request->lines)->sum('amount'),
            ]);

            foreach ($request->lines as $line) {
                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'description' => $line['description'],
                    'amount' => $line['amount'],
                ]);
            }

            return new InvoiceResource($invoice->load(['client', 'lines']));
        });
    }

    /**
     * @OA\Get(
     *     path="/api/v1/invoices/{id}",
     *     tags={"Invoices"},
     *     summary="Get an invoice by ID",
     *     security={{"Sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice details",
     *         @OA\JsonContent(ref="#/components/schemas/Invoice")
     *     )
     * )
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice->load(['client', 'lines']));
    }

     /**
     * @OA\Delete(
     *     path="/api/v1/invoices/{id}",
     *     tags={"Invoices"},
     *     summary="Delete an invoice",
     *     security={{"Sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
         required=true,
         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Invoice deleted"
     *     )
     * )
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/invoices/{id}/pdf",
     *     tags={"Invoices"},
     *     summary="Generate PDF for an invoice",
     *     security={{"Sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF generated",
     *         @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     )
     * )
     */
    public function generatePdf(Invoice $invoice, PdfGenerationService $pdfService)
    {
        $pdf = $pdfService->generateInvoicePdf($invoice);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-' . $invoice->invoice_number . '.pdf"',
        ]);
    }
    
}
