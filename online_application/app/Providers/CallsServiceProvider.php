<?php

namespace App\Providers;

use App\Services\Call\CallService;
use Illuminate\Support\ServiceProvider;

class CallsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('call', function () {
            return new CallService();
        });
    }
}
