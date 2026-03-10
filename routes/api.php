<?php

use App\Http\Controllers\Catalog\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('catalog')->group(function () {
    Route::prefix('movies')->group(function () {
        Route::post('/', [MovieController::class, 'store']);
        Route::patch('/{id}/publish', [MovieController::class, 'publish']);
        Route::patch('/{id}/archive', [MovieController::class, 'archive']);
    });
});
