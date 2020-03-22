<?php
namespace JohnesKe\MpesaPhpApi;

use Illuminate\Support\ServiceProvider;

class MpesaPhpApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/../config/mpesa.php', 'mpesa');

    }
}