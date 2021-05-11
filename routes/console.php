<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cache:all-clear', function () {
    //clear cache
    $this->comment('Clearing all type of caches...');
    $this->call('view:clear');
    $this->call('route:clear');
    $this->call('config:clear');
    $this->call('cache:clear');
})->describe('Clear all type of caches like view,route,config,data');
