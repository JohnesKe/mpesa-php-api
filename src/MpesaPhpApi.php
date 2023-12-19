<?php
namespace JohnesKe\MpesaPhpApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

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

  private $baseDomain;
  private $consumerKey;
  private $consumerSecret;

  public function __construct($environment,$consumer_key,$consumer_secret)
  {
    if($environment === 'sandbox') {
      $this->baseDomain = $this->sandbox_domain;
      $this->consumerKey = $consumer_key;
      $this->consumerSecret = $consumer_secret;
    } elseif($environment === 'live') {
      $this->baseDomain = $this->production_domain;
      $this->consumerKey = $consumer_key;
      $this->consumerSecret = $consumer_secret;
    }
  }

  // Get Token
  public function getToken()
  {
    $credentials = base64_encode($this->consumerKey.':'.$this->consumerSecret);

    $tokenClient = new Client([
      'base_uri' => $this->baseDomain,
      'headers' => [
          'Accept' => 'application/json',
          'Accept'       => 'application/json',
          'Authorization' => 'Bearer '.$credentials
      ]
    ]);

    // try {
    //   $response = $tokenClient->request('GET', $this->tokenUrl);

    //   $resp = $response->getBody()->getContents();

    //   //convert json to php objects
    //   $result_1 = json_decode($resp);

    //   return $result_1->access_token;

    // } catch (ClientException $e) {
    //     echo Psr7\Message::toString($e->getRequest());
    //     echo Psr7\Message::toString($e->getResponse());
    // }

    $promise = $tokenClient->requestAsync('GET', $this->tokenUrl);
    $promise->then(
        function (ResponseInterface $response) {

            $code = $response->getStatusCode();

            if($code === 200){
              $resp = $response->getBody()->getContents();

              //convert json to php objects
              $result = json_decode($resp);

              return $result->access_token;
            }
        },
        function (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
    );
  }

  public function stkPushRequest($access_token,$shortcode,$passkey,$amount,$phoneNumber,$callBackUrl,
          $accountReference,$transactionDescription){

    $timestamp = date("Ymdhis");
    $password  = base64_encode($shortcode.$passkey.$timestamp);

    $data = array(
        'BusinessShortCode' => $shortcode,
        'Password'         => $password,
        'Timestamp'        => $timestamp,
        'TransactionType'  => "CustomerPayBillOnline",
        'Amount'           => $amount,
        'PartyA'           => $phoneNumber,
        'PartyB'           => $shortcode,
        'PhoneNumber'      => $phoneNumber,
        'CallBackURL'      => $callBackUrl,
        'AccountReference' => $accountReference,
        'TransactionDesc'  => $transactionDescription
    );

    $response = $this->postRequest($access_token,$this->stkUrl,json_encode($data));

    return json_decode($response);
  }

  public function stkPushQuery($access_token,$shortcode,$passkey,$checkoutRequestID){

    $timestamp = date("Ymdhis");
    $password  = base64_encode($shortcode.$passkey.$timestamp);

    $data = array(
      'BusinessShortCode' => $shortcode,
      'Password'          => $password,
      'Timestamp'         => $timestamp,
      'CheckoutRequestID' => $checkoutRequestID
    );

    $response = $this->postRequest($access_token,$this->stkQuery, json_encode($data));

    return json_decode($response);
  }

  public function c2b_paybill_online($access_token,$shortcode,$amount,$phoneNumber,$billRefNumber){

    $data = array(
      'ShortCode'     => $shortcode,
      'CommandID'     => "CustomerPayBillOnline",
      'amount'        => $amount,
      'MSISDN'        => $phoneNumber,
      'BillRefNumber' => $billRefNumber
    );

    $response = $this->postRequest($access_token,$this->c2b_paybill_online_url,json_encode($data));

    return json_decode($response);
  }

  public function register_c2b_urls($access_token,$shortcode,$confirmUrl,$validationUrl){

    $data = array(
      'ShortCode'       => $shortcode,
      'ResponseType'    => "Completed",
      'ConfirmationURL' => $confirmUrl,
      'ValidationURL'   => $validationUrl
    );

    $response = $this->postRequest($access_token,$this->c2b_register_urls_url,json_encode($data));

    return json_decode($response);
  }

  public function c2b_customer_buy_goods($access_token,$shortcode,$amount,$phoneNumber,$billRefNumber){

    $data = array(
      'ShortCode' => $shortcode,
      'CommandID' => "CustomerBuyGoodsOnline",
      'amount' => $amount,
      'MSISDN'   => $phoneNumber,
      'BillRefNumber' =>$billRefNumber,
    );

    $response = $this->postRequest($access_token,$this->c2b_customer_buy_goods_url,json_encode($data));

    return json_decode($response);
  }

  public function b2c_payment_request($access_token,$initiatorName,$securityCredential,$commandID,
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

    $response = $this->postRequest($access_token,$this->b2c_payment_request_url,json_encode($data));

    return json_decode($response);
  }

  private function postRequest($access_token,$url,$data){

    $httpClient = new Client([
      'base_uri' => $this->baseDomain,
      'headers' => [
          'Authorization' => 'Bearer '.$access_token,
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json'
      ]
    ]);

    try{
        $response = $httpClient->post( $url, ['body' => $data ] );

        $resp = $response->getBody()->getContents();

        return $resp; 

    } catch (ClientException $e) {
        echo Psr7\Message::toString($e->getRequest());
        echo Psr7\Message::toString($e->getResponse());
    }
  }

}