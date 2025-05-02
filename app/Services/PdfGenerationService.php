<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Service responsible for generating PDF documents for invoices.
 */
class PdfGenerationService
{
    /**
     * Generate a PDF for the given invoice.
     *
     * @param Invoice $invoice The invoice to generate a PDF for
     * @return \Barryvdh\DomPDF\PDF The generated PDF instance
     */
    public function generateInvoicePdf(Invoice $invoice)
    {
        // Eager load relationships to avoid N+1 queries
        $invoice->load(['client', 'lines']);

        // Prepare data for the view
        $data = [
            'invoice' => $invoice,
            'client' => $invoice->client,
            'lines' => $invoice->lines,
        ];

        // Load the Blade view and generate PDF
        $pdf = Pdf::loadView('pdf.invoice', $data);

        // Optional: Set PDF options (e.g., paper size, DPI)
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }
}