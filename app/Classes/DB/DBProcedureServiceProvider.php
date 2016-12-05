<?php

namespace App\Classes\DB;

use Illuminate\Support\ServiceProvider;

class DBProcedureServiceProvider extends ServiceProvider {

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
        $this->app->singleton('dbprocedure', function () {
            return new DBProcedure;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['dbprocedure'];
    }

}
