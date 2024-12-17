<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/hitungHargaBeforeTax', [APIController::class, 'calculatePriceBeforeTax']);
Route::post('/hitungTotalDiskonLevel', [APIController::class, 'calculateTotalDiscountLevel']);
Route::post('/hitungShareRevenueOjek', [APIController::class, 'calculateShareRevenue']);
