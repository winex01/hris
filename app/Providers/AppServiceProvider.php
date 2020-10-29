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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('hris.log_query')) {
            \DB::listen(function($query) {
                \Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }
    }
}
