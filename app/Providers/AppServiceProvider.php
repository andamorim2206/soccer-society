<?php

namespace App\Providers;

use App\Repositories\MatchGameRepository;
use App\Repositories\MatchGameRepositoryInterface;
use App\Repositories\PlayerRepository;
use App\Repositories\PlayerRepositoryInterface;
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
