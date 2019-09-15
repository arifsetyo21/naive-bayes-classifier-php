<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StemmerServiceProvider extends ServiceProvider
{
    protected $stemm;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('stemm', function(){
            return $this->stemm->createStemmer();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->stemm = new \Sastrawi\Stemmer\StemmerFactory();
    }
}
