<?php

namespace App\Classes\ViewHelper;

use Illuminate\Support\ServiceProvider;

class ViewHelperServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('viewhelper', function () {
            return new ViewHelper;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['viewhelper'];
    }

}
