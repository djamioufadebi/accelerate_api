<?php

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pdf', function () {
    $pdf = PDF::loadView('pdf.test');
    return $pdf->download('test.pdf');
});

Route::get('/test-invoice-view/{invoice}', function (Invoice $invoice) {
    return view('pdf.invoice', ['invoice' => $invoice->load(['client', 'lines'])]);
});
