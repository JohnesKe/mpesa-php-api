<?php
namespace JohnesKe\MpesaPhpApi;

use Illuminate\Support\ServiceProvider;

use JohnesKe\MpesaPhpApi\Commands\RegisterC2BUrls;
use JohnesKe\MpesaPhpApi\MpesaPhpApi;

class MpesaPhpApiServiceProvider extends ServiceProvider
{
    /**
     * Package path to config.
     */
    const CONFIG_PATH = __DIR__ . '/../config/mpesa-php-api.php';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('mpesa-php-api.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                RegisterC2BUrls::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/../config/mpesa-php-api.php', 'mpesa');

        $this->mergeConfigFrom(self::CONFIG_PATH, 'mpesa-php-api');

        $this->app->bind('mpesa-php-api', function () {
            return new MpesaPhpApi();
        });

    }
}