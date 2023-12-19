# M-pesa PHP API FOR LARAVEL FRAMEWORK

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

### IF YOU WANT TO GO LIVE/PRODUCTION READY

Copy the variables in the `.env.example`.  to the `.env` environment file .
For the `MPESA_ENVIRONMENT` use `live` as the value for the production environment.

## Usage

If you have not created your Safaricom Daraja Mpesa API application yet you can create one at [Safaricom Developer][link-safaricom-developer]

Example code to try

``` php

<?php

    use JohnesKe\MpesaPhpApi\MpesaPhpApi;

    $mpesa = new MpesaPhpApi(
            config('mpesa-php-api.mpesa_environment'),
            config('mpesa-php-api.consumer_key'),
            config('mpesa-php-api.consumer_secret')
        );

    $accessToken = $mpesa->getToken();

    $amount = '1';
    $phoneNumber = '2547********';
    $callBackUrl = 'https:://example.com';
    $accountReference = 'Test001';
    $transactionRef = 'test';

   // Stk Push Example
    $result = $mpesa->stkPushRequest(
        $accessToken,
        config('mpesa-php-api.c2b_stk_short_code'),
        config('mpesa-php-api.lipa_na_mpesa_pass_key'),
        $amount,
        $phoneNumber,
        $callBackUrl,
        $accountReference,
        $transactionRef,
    );

    $ResponseCode        = $result->ResponseCode;
    $ResponseDescription = $result->ResponseDescription;
    $merchantRequestID   = $result->MerchantRequestID;
    $checkoutRequestID   = $result->CheckoutRequestID;
    $CustomerMessage     = $result->CustomerMessage;
    
    //Response   
    echo "<br/>Response Code --> ".$ResponseCode;
    echo "<br/>Response Desc --> ".$ResponseDescription;
    echo "<br/>Merchant Request ID --> ".$merchantRequestID;
    echo "<br/>Checkout Request ID--> ".$checkoutRequestID;
    echo "<br/>Customer Message --> ".$CustomerMessage;

```

## Security

If you discover any security related issues, please send an email to `jmecha09@gmail.com`
instead of using the issue tracker.

[link-safaricom-developer]: https://developer.safaricom.co.ke/
