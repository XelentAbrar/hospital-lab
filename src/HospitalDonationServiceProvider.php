<?php

namespace XelentAbrar\HospitalDonation;

use Illuminate\Support\ServiceProvider;

class HospitalDonationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HospitalDonation');
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'jspages');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/donation.php' => config_path('donation.php'),
        ], 'config');


        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages'),
        ], 'donation-vue');

        // $this->publishes([
        //     __DIR__.'/routes/admin' => resource_path('./../routes/admin'),
        // ], 'donation-routes');
        
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/donation.php', 'donation');
    }
}
