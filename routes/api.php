<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\InvoiceController;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource("users", UserController::class);
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('invoices', InvoiceController::class)->except(['update']);
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf']);
        Route::get('/history', [InvoiceController::class, 'history']);
    });
});