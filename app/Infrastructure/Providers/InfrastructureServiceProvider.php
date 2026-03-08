<?php

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
