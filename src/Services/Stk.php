<?php
namespace JohnesKe\MpesaPhpApi\Services;

use JohnesKe\MpesaPhpApi\MpesaPhpApiHttpClient;

class Stk extends MpesaPhpApiHttpClient{

  /**
   * Mpesa API Stk push endpoint.
   *
   * @var string
   */
  protected $stkEndpoint = 'mpesa/stkpush/v1/processrequest';

  /**
   * Mpesa API Stk Push query status endpoint.
   *
   * @var string
   */
  protected $statusEndPoint = 'mpesa/stkpushquery/v1/query';

  /**
   * Business short code that will receive the money.
   *
   * @var string
   */
  protected $shortCode;

  /**
   * The passkey associated with the short code.
   *
   * @var string
   */
  protected $passKey;

   
  public function __construct($mobileNo, $amount, $description, $accountReference, $callbackURL){

    parent::__construct();

    $this->shortCode   = config('mpesa-php-api.stk_push.short_code');
    $this->passKey     = config('mpesa-php-api.stk_push.pass_key');

    $this->push($mobileNo, $amount, $description, $accountReference, $callbackURL);
  
  }
            
  /**
  * Initiate an STK push to a Safaricom mobile number.
  *
  * @param string $mobileNo
  * @param string $amount default KES 10
  * @param string $description
  * @param string $callbackURL
  * @param string $accountReference
  * @return mixed
  */
  protected function push($mobileNo, $amount = 10, $description, $accountReference, $callbackURL)
  {
    // Validate mobile no
    if (empty($mobileNo)) {
      throw new \InvalidArgumentException(
        'Safaricom Mpesa enabled Phonenumber has not been set.');
    }

    if (empty($description)) {
      throw new \InvalidArgumentException(
        'Transaction Description info has not been set');
    }

    if (empty($accountReference)) {
      throw new \InvalidArgumentException(
        'Account Reference info has not been set');
    }

    if (empty($callbackURL)) {
      throw new \InvalidArgumentException(
        'CallBackURL has not been set');
    }

    $timestamp = date('YmdHis');

    $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

    $parameters = [
      'BusinessShortCode' => $this->shortCode,
      'Password' => $password,
      'Timestamp' => $timestamp,
      'TransactionType' => 'CustomerPayBillOnline',
      'Amount' => $amount,
      'PartyA' => $mobileNo,
      'PartyB' => $this->shortCode,
      'PhoneNumber' => $mobileNo,
      'CallBackURL' => $callbackURL,
      'AccountReference' => $accountReference,
      'TransactionDesc' => str_limit($description, 20, ''),
      'Remark'          => str_limit($description, 20, ''),
    ];

    return $this->call($this->stkEndpoint, ['json' => $parameters]);
  }

  /**
   * Check the status of a Lipa Na Mpesa Online Transaction.
   *
   * @param string $checkoutRequestId
   * @return mixed
   */
  public function transactionStatus($checkoutRequestId)
  {

    //validate the params
    if (empty($checkoutRequestId)) {
      throw new \InvalidArgumentException(
        'CheckoutRequestID is required');
    }

    $timestamp = date('YmdHis');

    $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

    $parameters = [
      'BusinessShortCode' => $this->shortCode,
      'Password' => $password,
      'Timestamp' => $timestamp,
      'CheckoutRequestID' => $checkoutRequestId,
    ];

    return $this->call($this->statusEndPoint, ['json' => $parameters]);
  }

}
