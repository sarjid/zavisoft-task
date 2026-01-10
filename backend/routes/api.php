<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController as AdminOrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AuthController::class, 'login']);

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/me', 'me');
            Route::post('/logout', 'logout');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::delete('/category/multiple-delete', 'multipleDelete');
            Route::put('/category/{category}/status', 'changeStatus');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::delete('/products/multiple-delete', 'multipleDelete');
            Route::put('/products/{product}/status', 'changeStatus');
            Route::get('/products/create-data', 'createData');
            Route::get('/products/attribute-values', 'attributeValues');
            Route::post('/editor-file/upload', 'editorFileUpload');
        });

        Route::controller(AttributeController::class)->group(function () {
            Route::delete('/attributes/multiple-delete', 'multipleDelete');
        });

        Route::controller(AttributeValueController::class)->group(function () {
            Route::delete('/attribute-values/multiple-delete', 'multipleDelete');
        });

        Route::controller(AdminOrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/{order}', 'show');
        });

        Route::resource('attributes', AttributeController::class);
        Route::resource('attribute-values', AttributeValueController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

Route::controller(FrontendController::class)->group(function () {
    Route::get('/products', 'products');
    Route::get('/categories', 'categories');
    Route::get('/products/slug/{slug}', 'product');
});

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/products/{product}', [ProductController::class, 'show']);
