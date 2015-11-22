<?php

namespace Pronto\Providers;


use Illuminate\Support\ServiceProvider;
use ParsedownExtra;
use Pronto\Markdown\Parser;

class ParsingServiceProvider extends ServiceProvider
{
    /**
        * Register bindings in the container.
        *
        * @return void
        */
        public function register()
        {
            $this->app->singleton('Pronto\Markdown\Parser', function ($app) {
                
                $Parsedown = new ParsedownExtra();
            
                // enables automatic line breaks
                $Parsedown->setBreaksEnabled(true);
                
                // automatic linking of URLs
                $Parsedown->setUrlsLinked(true);
                
                $parser = new Parser($Parsedown);
                
                return $parser;
            });

        }
}
