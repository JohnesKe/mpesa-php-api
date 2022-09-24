<?php
require '../vendor/autoload.php';

use JohnesKe\MpesaPhpApi\Mpesa;

    // Looing for .env at the root directory
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable('../');
    $dotenv->load();

    $mpesa = new Mpesa(
        getenv('MPESA_ENVIRONMENT'), 
        getenv('MPESA_CONSUMER_KEY'),
        getenv('MPESA_CONSUMER_SECRET')
    );

    $token = $mpesa->token();

    echo "Mpesa Token --> ".$token;

?>