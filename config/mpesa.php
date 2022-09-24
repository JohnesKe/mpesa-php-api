<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mpesa API Settings
    |--------------------------------------------------------------------------
    |
    | This file contains sandbox mpesa settings. For live/production settings
    | please set them in the .env file
    |
    */

    'consumer_key'        => env('MPESA_CONSUMER_KEY','SU3oHOJFCmpGsyoe6U49hAPG0dy7rqT5'),

    'consumer_secret'     => env('MPESA_CONSUMER_SECRET','IttCad6e37CksH2X'),

    'mpesa_environment'   => env('MPESA_ENVIRONMENT','sandbox'),

    'business_short_code' => env('BUSINESS_SHORT_CODE','174379'),

    'mpesa_pass_key'      => env('LIPA_NA_MPESA_PASS_KEY','bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),

    'call_back_url'       => env('CALL_BACK_URL'),

    'transaction_type'    => env('TRANSACTION_TYPE','CustomerPayBillOnline'),

    'amount'              => env('AMOUNT','10'),

    'phonenumber'         => env('PHONENUMBER'),

    'account_reference'   => env('ACCOUNT_REFERENCE','Test001'),

    'trx_description'     => env('TRX_DESCRIPTION','Test001'),

    'remarks'             => env('REMARKS','Test00l'),

];
