<?php

namespace App\Classes\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot() {
        
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('storage', function () {
            return new Storage;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['storage'];
    }

}
