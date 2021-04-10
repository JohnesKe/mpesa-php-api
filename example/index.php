<?php
require '../vendor/autoload.php';
require 'config.php';

use JohnesKe\MpesaPhpApi\MpesaPhpApi;

$mpesa = new MpesaPhpApi(MPESA_ENV, MPESA_CONSUMER_KEY , MPESA_CONSUMER_SECRET);

$token = $mpesa->token();

echo "MpesaPhpApi Token --> ".$token;

?>