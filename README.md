## M-pesa PHP API FOR LARAVEL FRAMEWORK

This package provides you with a simple tool to make requests to Safaricom Mpesa APIs so that you can focus on the development of your awesome application.

## Installation

Install via composer
```bash
composer require johnes-ke/mpesa-php-api
```

### Publish Configuration File

```bash
php artisan vendor:publish --tag=mpesa-php-api-config
```

### Publish Database Migration File

```bash
php artisan vendor:publish --tag=mpesa-php-api-migrations
```

Fill in all your production Mpesa API details in the `.env` file. Here are the env variables for quick copy paste. For the `MPESA_ENVIRONMENT` use `live` as the value for production. 

```
MPESA_CONSUMER_KEY=

MPESA_CONSUMER_SECRET=

MPESA_ENVIRONMENT=

MPESA_C2B_STK_SHORTCODE=

LIPA_NA_MPESA_PASS_KEY=

```

## Usage

If you have not created your Safaricom Daraja Mpesa API application yet you can create one at [Safaricom Developer][link-safaricom-developer]

Example code to try

``` php

    use JohnesKe\MpesaPhpApi\MpesaPhpApi;

    $mpesa = new MpesaPhpApi(getenv('MPESA_ENVIRONMENT'),getenv('MPESA_CONSUMER_KEY'),getenv('MPESA_CONSUMER_SECRET'));

    $accessToken = $mpesa->getToken();

    $amount = '1';
    $phoneNumber = '2547********';
    $callBackUrl = 'https:://example.com';
    $accountReference = 'Test001';
    $transactionRef = 'test';

    $stkResponse = $mpesa->stkPushRequest(
        $accessToken,
        getenv('MPESA_C2B_STK_SHORTCODE'),
        getenv('LIPA_NA_MPESA_PASS_KEY'),
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

```

## Security

If you discover any security related issues, please send an email to jmecha09@gmail.com
instead of using the issue tracker.

[link-safaricom-developer]: https://developer.safaricom.co.ke/ 
