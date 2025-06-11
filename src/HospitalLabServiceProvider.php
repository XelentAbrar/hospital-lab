<?php

namespace XelentAbrar\HospitalLab;

use Illuminate\Support\ServiceProvider;

class HospitalLabServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HospitalLab');
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'jspages');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/lab.php' => config_path('lab.php'),
        ], 'config');


        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages'),
        ], 'lab-vue');

        // $this->publishes([
        //     __DIR__.'/routes/admin' => resource_path('./../routes/admin'),
        // ], 'lab-routes');
        
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lab.php', 'lab');
    }
}
