<?php
require '../vendor/autoload.php';
require 'config.php';

use JohnesKe\MpesaPhpApi\Mpesa;

$mpesa = new Mpesa( MPESA_ENV,MPESA_CONSUMER_KEY,MPESA_CONSUMER_SECRET );

$token = $mpesa->token();

echo "Mpesa Token --> ".$token;
echo "<br/>";

$stkResponse = $mpesa->stkRequest( 
                                    $token,
                                    BUSINESS_SHORT_CODE,
                                    LIPA_NA_MPESA_PASS_KEY,
                                    TRANSACTION_TYPE,
                                    AMOUNT,
                                    PHONENUMBER,
                                    BUSINESS_SHORT_CODE,
                                    PHONENUMBER,
                                    CALL_BACK_URL,
                                    ACCOUNT_REFERENCE,
                                    TRX_DESCRIPTION,
                                    REMaRKS
                                  );

    //convert json to php objects
    $result = json_decode($stkResponse);

    $ResponseCode        = $result->ResponseCode;
    $ResponseDescription = $result->ResponseDescription;
    $merchantRequestID   = $result->MerchantRequestID;
    $checkoutRequestID   = $result->CheckoutRequestID;
    $CustomerMessage     = $result->CustomerMessage;
        
    echo "<br/>Response Code --> ".$ResponseCode;
    echo "<br/>Response Desc --> ".$ResponseDescription;
    echo "<br/>Merchant Request ID --> ".$merchantRequestID;
    echo "<br/>Checkout Request ID--> ".$checkoutRequestID;
    echo "<br/>Customer Message --> ".$CustomerMessage;

?>