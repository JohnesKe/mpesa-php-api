<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mpesa Php API Settings
    |--------------------------------------------------------------------------
    |
    | This file contains config settings for sandbox mpesa settings.
    | 
    | For live/production settings please set them in the .env file
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Consumer Key
    |--------------------------------------------------------------------------
    |
    | This value is the consumer key provided for your developer application.
    | The package needs this to make requests to the Mpesa APIs Endpoint.
    |
    */

    'consumer_key' => env('MPESA_CONSUMER_KEY','SU3oHOJFCmpGsyoe6U49hAPG0dy7rqT5'),

    /*
    |--------------------------------------------------------------------------
    | Consumer Secret
    |--------------------------------------------------------------------------
    |
    | This value is the consumer secret provided for your developer application.
    | The package needs this to make requests to the Mpesa APIs Endpoint.
    |
    */

    'consumer_secret' => env('MPESA_CONSUMER_SECRET','IttCad6e37CksH2X'),

    /*
    |--------------------------------------------------------------------------
    | Mpesa Environment
    |--------------------------------------------------------------------------
    |
    | This value sets the environment at which you are using the API.
    | Acceptable values are sandbox or live
    |
    |
    */

    'mpesa_environment' => env('MPESA_ENVIRONMENT','sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Initiator Details
    |--------------------------------------------------------------------------
    |
    | This array takes the credentials of the short code initiating transactions
    | to the MPESA APIs. The name is the username for the short code and
    | credential is the password.
    |
    | possible values for type: paybill, till, msisdn
    */

    'initiator' => [
        'name' => env('MPESA_INITIATOR_NAME', ''),
        'credential' => env('MPESA_INITIATOR_CREDENTIAL', ''),
        'short_code' => env('MPESA_INITIATOR_SHORTCODE', '174379'),
        'type' => env('MPESA_INITIATOR_TYPE', 'paybill'),
    ],

    /*
    |--------------------------------------------------------------------------
    | C2B URLs
    |--------------------------------------------------------------------------
    |
    | If you will be using the C2B API you can set the URLs that will handle the
    | validation and confirmation here. This will enable you to run the
    | artisan command to automatically register them. You can use a route name or
    | specific URL since we can not use the route() helper here
    |
    */

    'c2b_url' => [
      'confirmation' => env('MPESA_C2B_CONFIRMATION_URL', ''),
      'validation' => env('MPESA_C2B_VALIDATION_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Timeout URLs
    |--------------------------------------------------------------------------
    |
    | Here you can set the URLs that will handle the queue timeout response from
    | each of the APIs from MPESA.
    | You can use a route name or specific URL since we can not use the
    | route() helper here
    */

    'queue_timeout_url' => [
        'b2c' => env('MPESA_B2C_QUEUE_TIMEOUT_URL', ''),
        'b2b' => env('MPESA_B2B_QUEUE_TIMEOUT_URL', ''),
        'reversal' => env('MPESA_REVERSAL_QUEUE_TIMEOUT_URL', ''),
        'balance' => env('MPESA_BALANCE_QUEUE_TIMEOUT_URL', ''),
        'transaction_status' => env('MPESA_TRANSACTION_STATUS_QUEUE_TIMEOUT_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Result URLs
    |--------------------------------------------------------------------------
    |
    | Here you can set the URLs that will handle the results from each of the
    | APIs from MPESA. You can use a route name or specific URL You can
    | use a route name or specific URL since we can not use the
    | route() helper here
    |
    */

    'result_url' => [
        'b2c' => env('MPESA_B2C_RESULT_URL', ''),
        'b2b' => env('MPESA_B2B_RESULT_URL', ''),
        'reversal' => env('MPESA_REVERSAL_RESULT_URL', ''),
        'balance' => env('MPESA_BALANCE_RESULT_URL', ''),
        'transaction_status' => env('MPESA_TRANSACTION_STATUS_RESULT_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | STK Push
    |--------------------------------------------------------------------------
    |
    | Here you can set the details to be used for the STK push. The callback
    | URL can take a route name or a specific URL since we can not use
    | the route() helper here.
    |
    */

    'stk_push' => [

        'transaction_type' => env('MPESA_STK_PUSH_TRANSACTION_TYPE','CustomerPayBillOnline'),

        'short_code'   => env('MPESA_STK_PUSH_SHORTCODE','174379'),

        'callback_url' => env('MPESA_STK_PUSH_CALLBACK_URL',''),
        
        'pass_key'     => env('MPESA_STK_PUSH_PASS_KEY','bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    |
    | Here you can set your logging requirements. If enabled a new file will
    | will be created in the logs folder and will record all requests
    | and responses to the MPESA APIs. 
    | You can use the Monolog debug levels.
    |
    */

    'logs' => [
        'enabled' => env('MPESA_LOGS_ENABLED', false),
        'level' => env('MPESA_LOGS_LEVEL','DEBUG'),
    ],

];

