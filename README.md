## M-pesa PHP API FOR LARAVEL

This package provides you with a simple tool to make requests to Safaricom Mpesa APIs so that you can focus on the development of your awesome application.

## Installation

Install via composer
```bash
composer require johnes-ke/mpesa-php-api v1.0.1-beta
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
JohnesKe\MpesaPhpApi\MpesaPhpApiServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
JohnesKe\MpesaPhpApi\Facade\MpesaPhpApi::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="JohnesKe\MpesaPhpApi\MpesaPhpApiServiceProvider" --tag="config"
```

Fill in all your production Mpesa API details. Here are the env variables for quick copy paste. For the `MPESA_ENVIRONMENT` use `live` as the value. 

```
MPESA_CONSUMER_SECRET=
MPESA_CONSUMER_KEY=
MPESA_ENVIRONMENT=

MPESA_STK_PUSH_SHORTCODE=
MPESA_STK_PUSH_PASS_KEY=
MPESA_STK_PUSH_CALLBACK_URL=

MPESA_C2B_CONFIRMATION_URL=
MPESA_C2B_VALIDATION_URL=

MPESA_INITIATOR_NAME=
MPESA_INITIATOR_CREDENTIAL=
MPESA_INITIATOR_SHORTCODE=
MPESA_INITIATOR_TYPE=

MPESA_B2C_QUEUE_TIMEOUT_URL=
MPESA_B2B_QUEUE_TIMEOUT_URL=
MPESA_REVERSAL_QUEUE_TIMEOUT_URL=
MPESA_BALANCE_QUEUE_TIMEOUT_URL=
MPESA_TRANSACTION_STATUS_QUEUE_TIMEOUT_URL=

MPESA_B2C_RESULT_URL=
MPESA_B2B_RESULT_URL=
MPESA_REVERSAL_RESULT_URL=
MPESA_BALANCE_RESULT_URL=
MPESA_TRANSACTION_STATUS_RESULT_URL=

MPESA_LOGS_ENABLED=
MPESA_LOGS_LEVEL=

```

## Usage

If you have not created your Safaricom Daraja Mpesa API application yet you can create one at [Safaricom Developer][link-safaricom-developer]

Each Mpesa API except Oauth has been implemented as a class on its own which you can use in your code.


``` php

$mpesa_api = new MpesaPhpApi();
$mpesa_api->stk_push('2547********', '10', 'Product description', 'Product Reference');

```

If you prefer using the facade

``` php
MpesaPhpApi::stk_push('2547********', '10', 'Product description', 'Product Reference');
```

If you will be using the C2B Api you can easily register the validation and confirmation URLs through artisan command.

``` bash
# php artisan mpesa-php-api:register-c2b-urls
```

## Security

If you discover any security related issues, please send an email to jmecha09@gmail.com
instead of using the issue tracker.

[link-safaricom-developer]: https://developer.safaricom.co.ke/ 
