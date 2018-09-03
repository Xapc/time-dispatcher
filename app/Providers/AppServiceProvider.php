<?php

namespace App\Providers;

use App\Services\OperatingTimeCSVImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::statement('PRAGMA foreign_keys=on');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OperatingTimeCSVImport::class);
    }
}
