<?php
namespace App\Traits;

use Illuminate\Support\Facades\Artisan;

trait CacheClear
{
    protected static function boot()
    {
        parent::boot();

        /**
         * After model is created, or whatever action, clear cache.
         */
        static::updated(function () {
            Artisan::call('cache:clear');
        });
    }
}
