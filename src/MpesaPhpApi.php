<?php

namespace JohnesKe\MpesaPhpApi;

use JohnesKe\MpesaPhpApi\Api\Stk;
use JohnesKe\MpesaPhpApi\Api\Token;

class MpesaPhpApi
{

  //Initiate the API
  public function init(
                        $environment,
                        $consumer_key,
                        $consumer_secret,
                        $businessShortCode,
                        $lipaNaMpesaPassKey
                      ){

    $mpesa = new Token(
                        $environment,
                        $consumer_key,
                        $consumer_secret,
                        $businessShortCode,
                        $lipaNaMpesaPassKey
                      );

    return $mpesa->getToken();
  }
  
  /**
   * Initiate a LIPA NA MPESA STK push transaction.
   *
   * @return Stk
   */
  public function stkPushRequest(
                                  $amount,
                                  $partyA,
                                  $partyB,
                                  $phoneNumber,
                                  $callBackUrl,
                                  $accountReference,
                                  $transactionDescription
                                ){
    
    $stk = new Stk();

    $response = $stk->stkPushRequest(
                                  $amount,
                                  $partyA,
                                  $partyB,
                                  $phoneNumber,
                                  $callBackUrl,
                                  $accountReference,
                                  $transactionDescription
                                );
    return $response;
  }

  public function stk_transaction_status($checkoutRequestID){
    $stk = new Stk();
    $response = $stk->stkPushQuery($checkoutRequestID);
    return $response;
  }

}