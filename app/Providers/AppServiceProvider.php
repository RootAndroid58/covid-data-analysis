<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $cli_memory_limit = config('app.cli_memory_limit');
        if (php_sapi_name() === 'cli' && !empty($cli_memory_limit)) {
            ini_set('memory_limit', $cli_memory_limit);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
