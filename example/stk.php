<?php
require '../vendor/autoload.php';

use JohnesKe\MpesaPhpApi\Mpesa;

$mpesa = new Mpesa(getenv('MPESA_ENVIRONMENT'),getenv('MPESA_CONSUMER_KEY'),getenv('MPESA_CONSUMER_SECRET'));

$token = $mpesa->token();

echo "Mpesa Token --> ".$token;
echo "<br/>";

$stkResponse = $mpesa->stkRequest( $token, getenv('BUSINESS_SHORT_CODE'),
                                    getenv('LIPA_NA_MPESA_PASS_KEY'),
                                    getenv('TRANSACTION_TYPE'),
                                    getenv('AMOUNT'),
                                    getenv('PHONENUMBER'),
                                    getenv('BUSINESS_SHORT_CODE'),
                                    getenv('PHONENUMBER'),
                                    getenv('CALL_BACK_URL'),
                                    getenv('ACCOUNT_REFERENCE'),
                                    getenv('TRX_DESCRIPTION'),
                                    getenv('REMARKS'),
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