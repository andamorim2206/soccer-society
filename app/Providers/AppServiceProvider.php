<?php

namespace App\Providers;

use App\Http\Controllers\Repositories\MatchGameRepository;
use App\Http\Controllers\Repositories\MatchGameRepositoryInterface;
use App\Http\Controllers\Repositories\PlayerRepository;
use App\Http\Controllers\Repositories\PlayerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(PlayerRepositoryInterface::class, PlayerRepository::class);
         $this->app->bind(MatchGameRepositoryInterface::class, MatchGameRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
