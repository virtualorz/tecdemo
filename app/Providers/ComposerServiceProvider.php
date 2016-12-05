<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            '*', 'App\Http\ViewComposers\LocaleComposer'
        );
        //
        view()->composer(
            ['backend.layouts.master'], 'App\Http\ViewComposers\MasterBackendComposer'
        );
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
