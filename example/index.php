<?php
require '../vendor/autoload.php';
require 'config.php';

use JohnesKeMpesaPhpApi\Mpesa;

$mpesa = new Mpesa(MPESA_ENV, MPESA_CONSUMER_KEY , MPESA_CONSUMER_SECRET);

$token = $mpesa->token();

echo "Mpesa Token --> ".$token;

?>