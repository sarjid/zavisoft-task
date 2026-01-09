<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/admin/login', [AuthController::class, 'login']);

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/me', 'me');
        Route::post('/logout', 'logout');
    });


    Route::controller(CategoryController::class)->group(function () {
        Route::delete('/category/multiple-delete', 'multipleDelete');
        Route::put('/category/{category}/status', 'changeStatus');
    });
    Route::resource('category', CategoryController::class);
    Route::resource('products', ProductController::class);
});
