<?php

namespace JohnesKe\MpesaPhpApi;

class MpesaPhpApi
{
  private $sandbox_domain  = "https://sandbox.safaricom.co.ke";
  private $production_domain = "https://api.safaricom.co.ke";

  private $tokenUrl = '/oauth/v1/generate?grant_type=client_credentials';

  private $stkUrl = '/mpesa/stkpush/v1/processrequest';
  private $stkQuery = '/mpesa/stkpushquery/v1/query';

  private $c2b_paybill_online_url = '/mpesa/c2b/v1/simulate';
  private $c2b_register_urls_url = '/mpesa/c2b/v1/registerurl';
  private $c2b_customer_buy_goods_url = '/mpesa/c2b/v1/simulate';

  private $b2c_payment_request_url = '/mpesa/b2c/v1/paymentrequest';

  private $shortcode;
  private $passkey;
  private $baseDomain;
  private $access_token;

  public function __construct($environment,$consumer_key,$consumer_secret,$shortcode,$passkey)
  {
    if($environment === 'sandbox') {
      $this->baseDomain = $this->sandbox_domain;
      $this->access_token = $this->getToken(base64_encode($consumer_key.':'.$consumer_secret));
      $this->passkey = $passkey;
      $this->shortcode = $shortcode;
    } elseif($environment === 'live') {
      $this->baseDomain = $this->production_domain;
      $this->access_token = $this->getToken(base64_encode($consumer_key.':'.$consumer_secret));
      $this->passkey = $passkey;
      $this->shortcode = $shortcode;
    }
  }

  // Get Token
  private function getToken($credentials)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->baseDomain.$this->tokenUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$credentials, 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
  
    $response = json_decode($response);

    if ($info["http_code"] == 200) {
      return $response->access_token;
    } else {
      //Invalid Consumer key or secret
      return $response;
    }
  }

  public function stkPushRequest($amount,$phoneNumber,$callBackUrl,
          $accountReference,$transactionDescription){

    $timestamp = date("Ymdhis");
    $password  = base64_encode($this->shortcode.$this->passkey.$timestamp);

    $data = array(
        'BusinessShortCode' => $this->shortcode,
        'Password'         => $password,
        'Timestamp'        => $timestamp,
        'TransactionType'  => "CustomerPayBillOnline",
        'Amount'           => $amount,
        'PartyA'           => $phoneNumber,
        'PartyB'           => $this->shortcode,
        'PhoneNumber'      => $phoneNumber,
        'CallBackURL'      => $callBackUrl,
        'AccountReference' => $accountReference,
        'TransactionDesc'  => $transactionDescription
    );

    $response = $this->postRequest($this->stkUrl,json_encode($data));

    return json_decode($response);
  }

  public function stkPushQuery($checkoutRequestID){

    $timestamp = date("Ymdhis");
    $password  = base64_encode($this->shortcode.$this->passkey.$timestamp);

    $data = array(
      'BusinessShortCode' => $this->shortcode,
      'Password'          => $password,
      'Timestamp'         => $timestamp,
      'CheckoutRequestID' => $checkoutRequestID
    );

    $response = $this->postRequest($this->stkQuery, json_encode($data));

    return json_decode($response);
  }

  public function c2b_paybill_online($amount,$phoneNumber,$billRefNumber){

    $data = array(
      'ShortCode'     => $this->shortcode,
      'CommandID'     => "CustomerPayBillOnline",
      'amount'        => $amount,
      'MSISDN'        => $phoneNumber,
      'BillRefNumber' => $billRefNumber
    );

    $response = $this->postRequest($this->c2b_paybill_online_url,json_encode($data));

    return json_decode($response);
  }

  public function register_c2b_urls($confirmUrl,$validationUrl){

    $data = array(
      'ShortCode'       => $this->shortcode,
      'ResponseType'    => "Completed",
      'ConfirmationURL' => $confirmUrl,
      'ValidationURL'   => $validationUrl
    );

    $response = $this->postRequest($this->c2b_register_urls_url,json_encode($data));

    return json_decode($response);
  }

  public function c2b_customer_buy_goods($amount,$phoneNumber,$billRefNumber){

    $data = array(
      'ShortCode' => $this->shortcode,
      'CommandID' => "CustomerBuyGoodsOnline",
      'amount' => $amount,
      'MSISDN'   => $phoneNumber,
      'BillRefNumber' =>$billRefNumber,
    );

    $response = $this->postRequest($this->c2b_customer_buy_goods_url,json_encode($data));

    return json_decode($response);
  }

  public function b2c_payment_request($initiatorName,$securityCredential,$commandID,
                                      $amount,$b2c_shortcode,$phoneNumber,$remarks,$queueTimeOutUrl,
                                      $resultUrl,$occassion){
    
    $data = array(
      'InitiatorName' => $initiatorName,
      'SecurityCredential' => $securityCredential,
      'CommandID' => $commandID,
      'Amount'   => $amount,
      'PartyA' => $b2c_shortcode,
      'PartyB' => $phoneNumber,
      'Remarks' => $remarks,
      'QueueTimeOutURL' => $queueTimeOutUrl,
      'ResultURL' => $resultUrl,
      'Occassion' => $occassion
    );

    $response = $this->postRequest($this->b2c_payment_request_url,json_encode($data));

    return json_decode($response);
  }

  private function postRequest($url,$data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->baseDomain.$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->access_token, 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response     = curl_exec($ch);
    curl_close($ch);
    return $response;
  }

}