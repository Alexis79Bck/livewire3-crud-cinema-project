<?php

/**
 * Proveedor de servicios de infraestructura.
 *
 * Este Service Provider de Laravel es responsable de registrar las
 * implementaciones de la capa de infraestructura en el contenedor
 * de dependencias de la aplicación.
 *
 * El proveedor une las interfaces del dominio con sus implementaciones
 * concretas en la capa de infraestructura, siguiendo el principio de
 * Inversión de Dependencias (DIP) del diseño orientado a objetos.
 *
 * Servicios registrados:
 * - MovieRepository: Implementación Eloquent para el repositorio de películas
 *
 * @see \Illuminate\Support\ServiceProvider Clase base de Laravel
 */

namespace App\Infrastructure\Providers;

use App\Domain\Catalog\Repositories\MovieRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentMovieRepository;
use Illuminate\Support\ServiceProvider;

class InfrastructureServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            MovieRepository::class,
            EloquentMovieRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
