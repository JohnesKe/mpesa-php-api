<?php
namespace JohnesKe\MpesaPhpApi\Services;

use JohnesKe\MpesaPhpApi\MpesaPhpApiHttpClient;
use Illuminate\Support\Str;

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
  protected $stkStatusEndPoint = 'mpesa/stkpushquery/v1/query';

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

  /**
   * The callback url where the mpesa api will send the result back from stkpush.
   *
   * @var string
   */
  protected $callBackUrl;

   
  public function __construct(){

    parent::__construct();

    $this->shortCode   = config('mpesa-php-api.stk_push.short_code');
    $this->passKey     = config('mpesa-php-api.stk_push.pass_key');
    $this->callBackUrl = config('mpesa-php-api.stk_push.callback_url');

  }
            
  /**
  * Initiate an STK push to a Safaricom mobile number.
  *
  * @param string $mobileNo
  * @param string $amount default KES 10
  * @param string $description
  * @param string $accountReference
  * @return mixed
  */
  public function request($mobileNo, $amount = 10, $description, $accountReference)
  {
    // Validate mobile no
    if (empty($mobileNo)) {
      throw new \InvalidArgumentException(
        'Safaricom Mpesa enabled Phonenumber has not been set.');
    }

    if (empty($description)) {
      throw new \InvalidArgumentException(
        'Mpesa Transaction Description info has not been set');
    }

    if (empty($accountReference)) {
      throw new \InvalidArgumentException(
        'Mpesa Account Reference info has not been set');
    }

    if (empty($this->callBackUrl)) {
      throw new \InvalidArgumentException(
        'Mpesa CallBackURL has not been set');
    }

    if (empty($this->shortCode)) {
      throw new \InvalidArgumentException(
        'Mpesa ShortCode has not been set');
    }

    if (empty($this->passKey)) {
      throw new \InvalidArgumentException(
        'Mpesa PassKey has not been set');
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
      'CallBackURL' => $this->callBackUrl,
      'AccountReference' => $accountReference,
      'TransactionDesc' => Str::limit($description, 20),
      'Remark'          => Str::limit($description, 20),
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
        'Mpesa CheckoutRequestID is required');
    }

    $timestamp = date('YmdHis');

    $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

    $parameters = [
      'BusinessShortCode' => $this->shortCode,
      'Password' => $password,
      'Timestamp' => $timestamp,
      'CheckoutRequestID' => $checkoutRequestId,
    ];

    return $this->call($this->stkStatusEndPoint, ['json' => $parameters]);
  }

}
