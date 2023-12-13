<?php
namespace JohnesKe\MpesaPhpApi;

use Illuminate\Support\ServiceProvider;

class MpesaApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'mpesa-api-config');

        if (! class_exists('CreateMpesaPhpApiTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_mpesa_php_api_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_mpesa_php_api_table.php'),
            ], 'mpesa-api-migrations');
        }

        // $this->publishes([
        //     __DIR__.'/../resources/views' => resource_path('views/vendor/mpesa-php-api'),
        // ], 'views');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mpesa.php', 'mpesa');
    }
}