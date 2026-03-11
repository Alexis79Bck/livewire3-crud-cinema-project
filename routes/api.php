<?php

/**
 * =============================================================================
 * RUTAS API DEL BOUNDED CONTEXT CATALOG
 * =============================================================================
 *
 * Este archivo define las rutas REST API para el Bounded Context Catalog.
 * Incluye endpoints para la gestión completa del catálogo de películas.
 *
 * Arquitectura:
 * - Las rutas están agrupadas bajo el prefijo 'catalog/movies'
 * - Utilizan el controlador MovieController del contexto Catalog
 * - Implementan el patrón Command Handler mediante CQRS
 *
 * Endpoints disponibles:
 * - POST /api/catalog/movies              -> Crear nueva película
 * - PATCH /api/catalog/movies/{id}/publish -> Publicar película
 * - PATCH /api/catalog/movies/{id}/archive -> Archivar película
 *
 * @see \App\Http\Controllers\Catalog\MovieController
 * @package Routes\Catalog
 * @since 1.0.0
 */

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
