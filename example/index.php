<?php
    require '../vendor/autoload.php';

    use JohnesKe\MpesaPhpApi\MpesaPhpApi;

    // Looking for .env.example at the root directory
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.example');
    $dotenv->load();

    $mpesa = new MpesaPhpApi();
    
    $token = $mpesa->init( 
                            getenv('MPESA_ENVIRONMENT'),
                            getenv('MPESA_CONSUMER_KEY'),
                            getenv('MPESA_CONSUMER_SECRET'),
                            getenv('BUSINESS_SHORT_CODE'),
                            getenv('LIPA_NA_MPESA_PASS_KEY'),
                        );
    
    echo "Mpesa Token --> ".$token;

    $stkResponse = $mpesa->stkPushRequest(
                            getenv('AMOUNT'),
                            getenv('PHONENUMBER'),
                            getenv('BUSINESS_SHORT_CODE'),
                            getenv('PHONENUMBER'),
                            getenv('CALL_BACK_URL'),
                            getenv('ACCOUNT_REFERENCE'),
                            getenv('TRX_DESCRIPTION'),
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