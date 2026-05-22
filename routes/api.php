<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    Route::get('/items', [ItemApiController::class, 'index']);
    Route::get('/items/{kode_barang}', [ItemApiController::class, 'show']);
});
