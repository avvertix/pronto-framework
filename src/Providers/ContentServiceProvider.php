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
        $this->app->bind('Pronto\Contracts\Content', 'Pronto\Content\Content');
    }
    
    public function boot(){
        view()->share('global_menu', content()->pages());
    }
}
