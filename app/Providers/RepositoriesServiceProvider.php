<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ChatRepositoryInterface;
use App\Repositories\ChatRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Contracts\ChatRepositoryInterface',
	        'App\Repositories\ChatRepository'
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
