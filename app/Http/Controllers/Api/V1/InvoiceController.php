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

class InvoiceController extends Controller
{
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

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice->load(['client', 'lines']));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(null, 204);
    }

    public function generatePdf(Invoice $invoice, PdfGenerationService $pdfService)
    {
        $pdf = $pdfService->generateInvoicePdf($invoice);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-' . $invoice->invoice_number . '.pdf"',
        ]);
    }
    
}
