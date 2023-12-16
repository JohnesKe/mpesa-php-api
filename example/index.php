<?php
    require '../vendor/autoload.php';

    use JohnesKe\MpesaPhpApi\MpesaPhpApi;

    // Looking for .env.example at the root directory
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.example');
    $dotenv->load();

    $mpesa = new MpesaPhpApi( 
                            getenv('MPESA_ENVIRONMENT'),
                            getenv('MPESA_CONSUMER_KEY'),
                            getenv('MPESA_CONSUMER_SECRET'),
                            getenv('MPESA_C2B_STK_SHORTCODE'),
                            getenv('LIPA_NA_MPESA_PASS_KEY'),
                        );

    $stkResponse = $mpesa->stkPushRequest($amount,
                        $phoneNumber,
                        $callBackUrl,
                        $accountReference,
                        $transactionRef,
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