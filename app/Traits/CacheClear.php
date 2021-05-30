<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheClear
{
    protected static function boot()
    {
        parent::boot();

        /**
         * After model is created, or whatever action, clear cache.
         */
        static::updated(function () {
            Cache::tags('prod')->flush();
        });
    }
}
