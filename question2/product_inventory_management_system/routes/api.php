<?php

use App\Http\Controllers\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [SiteController::class, 'login']);
Route::post('/logout', [SiteController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store']);
    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index']);
    Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show']);
    Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy']);

    Route::get('/setSupplier', [\App\Http\Controllers\ProductController::class, 'setSupplierProduct']);
    Route::delete('/removeSupplierProduct', [\App\Http\Controllers\ProductController::class, 'removeSupplierProduct']);
    Route::get('/findAllSupplierDetail', [\App\Http\Controllers\ProductController::class, 'findAllSupplierDetail']);

    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);

    Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store']);
    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class,  'index']);
    Route::get('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'show']);
    Route::put('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'update']);
    Route::delete('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'destroy']);
});
