<?php
namespace JohnesKe\MpesaPhpApi;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;

//use JohnesKe\MpesaPhpApi\Commands\RegisterC2BUrls;
//use JohnesKe\MpesaPhpApi\MpesaPhpApi;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Package path to config.
     */
    const CONFIG_PATH = __DIR__ . '/../config/mpesa-php-api.php';

    /**
    * Bootstrap any package services.
    */
    public function boot(): void
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('mpesa-php-api.php'),
        ],'mpesa-php-api-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'mpesa-php-api-migrations');

        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         RegisterC2BUrls::class,
        //     ]);
        // }

        //load any route files
        //$this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        //about this package
        AboutCommand::add('Mpesa Php Api', fn () => ['Version' => '2.0.4']);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // $this->mergeConfigFrom(self::CONFIG_PATH, 'mpesa-php-api');

        // $this->app->bind('mpesa-php-api', function () {
        //     return new MpesaPhpApi();
        // });

    }

}