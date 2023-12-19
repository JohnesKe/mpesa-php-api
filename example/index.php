<?php
    require '../vendor/autoload.php';
    
    use JohnesKe\MpesaPhpApi\MpesaPhpApi;

    $MPESA_CONSUMER_KEY = 'SU3oHOJFCmpGsyoe6U49hAPG0dy7rqT5';

    $MPESA_CONSUMER_SECRET = 'IttCad6e37CksH2X';

    $MPESA_ENVIRONMENT = 'sandbox';

    $MPESA_C2B_STK_SHORTCODE = '174379';

    $LIPA_NA_MPESA_PASS_KEY = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

    $mpesa = new MpesaPhpApi($MPESA_ENVIRONMENT,$MPESA_CONSUMER_KEY,$MPESA_CONSUMER_SECRET);

    $accessToken = $mpesa->getToken();

    $amount = '1';
    $phoneNumber = '254708377969';
    $callBackUrl = 'https:://payments.extreamkenya.co.ke';
    $accountReference = 'Test001';
    $transactionRef = 'test';

    $stkResponse = $mpesa->stkPushRequest(
        $accessToken,
        $MPESA_C2B_STK_SHORTCODE,
        $LIPA_NA_MPESA_PASS_KEY,
        $amount,
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