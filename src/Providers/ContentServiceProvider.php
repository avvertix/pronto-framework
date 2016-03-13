<?php

namespace Pronto\Providers;


use Illuminate\Support\ServiceProvider;
use ParsedownExtra;
use Pronto\Content\Content;

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // gets the configuration file
        $this->mergeConfigFrom(__DIR__ . '/../../config/pronto.php', 'pronto');

        // register the Content provider
        $this->app->singleton('Pronto\Contracts\Content', function ($app) {
            
            $c = new Content( config('pronto'), $app->make('Illuminate\Filesystem\Filesystem') );
            
            return $c;
        });
        
    }
    
    public function boot(){

        view()->share('global_menu', content()->global_navigation());
        
    }
}
