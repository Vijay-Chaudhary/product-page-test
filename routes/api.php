<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductDiscountController;
use App\Http\Controllers\Api\V1\ProductImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {
        Route::post('product', [ProductController::class, 'store'])->name('products.store');
        Route::match(['PATCH', 'PUT'], 'product/{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::get('products', [ProductController::class, 'index'])->name('products');
        Route::get('product/{slug}', [ProductController::class, 'show'])->name('products.show');
        Route::delete('product/{slug}', [ProductController::class, 'destroy'])->name('products.destroy');


        Route::get('product-discounts', [ProductDiscountController::class, 'index'])->name('product-discount');
        Route::get('product-discount/{productDiscount}', [ProductDiscountController::class, 'show'])->name('product-discount.show');
        Route::delete('product-discount/{productDiscount}', [ProductDiscountController::class, 'destroy'])->name('product-discount.destroy');

        Route::get('product-images', [ProductImageController::class, 'index'])->name('product-image');
        Route::get('product-image/{productImage}', [ProductImageController::class, 'show'])->name('product-image.show');
        Route::delete('product-image/{productImage}', [ProductImageController::class, 'destroy'])->name('product-image.destroy');
    });
});
