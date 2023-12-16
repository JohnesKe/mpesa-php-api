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
    | STK Push
    |--------------------------------------------------------------------------
    |
    | Here you can set the details to be used for the STK push. The callback
    | URL can take a route name or a specific URL since we can not use
    | the route() helper here.
    |
    */

    'c2b_stk_short_code' => env('MPESA_C2B_STK_SHORTCODE', '174379'),

    'lipa_na_mpesa_pass_key' => env('LIPA_NA_MPESA_PASS_KEY','bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),
    
];

