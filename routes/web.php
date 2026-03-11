<?php

/**
 * =============================================================================
 * RUTAS WEB DEL SISTEMA CINEMA
 * =============================================================================
 *
 * Este archivo define las rutas para la interfaz web del sistema de gestión
 * de cine. Actualmente contiene la ruta de bienvenida principal.
 *
 * Arquitectura:
 * - Las rutas web son independientes de las rutas API
 * - Renderizan vistas Blade para la interfaz de usuario
 * - Sirven como punto de entrada a la aplicación web
 *
 * Nota: Las funcionalidades principales del Bounded Context Catalog
 * se acceden a través de la API en routes/api.php.
 *
 * @package Routes\Web
 * @since 1.0.0
 */

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
