<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pdf', function () {
    $pdf = PDF::loadView('pdf.test');
    return $pdf->download('test.pdf');
});
