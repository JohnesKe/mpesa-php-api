<?php
namespace JohnesKe\MpesaPhpApi\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;      // for 400-level errors
use GuzzleHttp\Exception\ServerException;      // for 500-level errors
use GuzzleHttp\Exception\BadResponseException; // for both (it's their superclass)

use JohnesKe\MpesaPhpApi\MpesaPhpApiHttpClient;

class Stk extends MpesaPhpApiHttpClient{

    /**
     * Safaricom Lipa Na Mpesa Online API end point.
     *
     * @var string
     */
    protected $stkEndpoint = 'mpesa/stkpush/v1/processrequest';

    /**
     * Safaricom Lipa Na Mpesa Online API transaction status
     * end point.
     *
     * @var string
     */
    protected $statusEndPoint = 'mpesa/stkpushquery/v1/query';

    /**
     * Business short code that will receive money.
     *
     * @var string
     */
    protected $shortCode;

    /**
     * The passkey associated with Lipa Na Mpesa Online Transactions
     * for the short code.
     *
     * @var string
     */
    protected $passKey;

    /**
     * The url that will handle the result of the transaction from
     * the Saaricom Lipa Na Mpesa Online API.
     *
     * @var string
     */
    protected $callbackURL;

    protected $stkClient;
    protected $stkUrl;
    protected $businessShortCode;
    protected $lipaNaMpesaPassKey;
    protected $transactionType;
    protected $amount;
    protected $partyA;
    protected $partyB;
    protected $phoneNumber;
    protected $callBackUrl;
    protected $accountReference;
    protected $transactionDescription;
    protected $remarks;

    public function __construct($stkClient,$stkUrl,$businessShortCode,
                       $lipaNaMpesaPassKey,$transactionType,
                       $amount,$partyA,$partyB,$phoneNumber,
                       $callBackUrl,$accountReference,$trxDescription,
                       $remarks){

        parent::__construct();
        $this->shortCode = config('laravel-daraja.stk_push.short_code');
        $this->passKey = config('laravel-daraja.stk_push.pass_key');
        $this->callbackURL = $this->setUrl(config('laravel-daraja.stk_push.callback_url'));

        $this->stkClient              = $stkClient;
        $this->stkUrl                 = $stkUrl;
        $this->businessShortCode      = $businessShortCode;
        $this->lipaNaMpesaPassKey     = $lipaNaMpesaPassKey;
        $this->transactionType        = $transactionType;
        $this->amount                 = $amount;
        $this->partyA                 = $partyA;
        $this->partyB                 = $partyB;
        $this->phoneNumber            = $phoneNumber;
        $this->callBackUrl            = $callBackUrl;
        $this->accountReference       = $accountReference;
        $this->transactionDescription = $trxDescription;
        $this->remarks                = $remarks;

    }

    public function stkPushRequest(){

       try {
                    
            $timestamp ='20'.date("ymdhis");

            $password  = base64_encode(
                                       $this->businessShortCode.
                                       $this->lipaNaMpesaPassKey.
                                       $timestamp
                                   );
         
            $Data = array(
                'BusinessShortCode' => $this->businessShortCode,
                'Password'         => $password,
                'Timestamp'        => $timestamp,
                'TransactionType'  => $this->transactionType,
                'Amount'           => $this->amount,
                'PartyA'           => $this->partyA,
                'PartyB'           => $this->partyB,
                'PhoneNumber'      => $this->phoneNumber,
                'CallBackURL'      => $this->callBackUrl,
                'AccountReference' => $this->accountReference,
                'TransactionDesc'  => $this->transactionDescription,
                'Remark'           => $this->remarks
            );

            $data_string = json_encode($Data);

            $response = $this->stkClient->post( $this->stkUrl, ['body' => $data_string ] );

            $resp = $response->getBody()->getContents();

            return $resp; 

        } catch (ClientException | ServerException | BadResponseException $e) {
            // recover + log with monolog
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());

        } catch(\Exception $e){
          // recover + slightly different log with monolog
          echo $e->getMessage();

        }    
        
    }


    /**
     * Set the business short code that will be used for the transaction.
     *
     * @param string $code
     */
    public function setShortCode($code)
    {
        $this->shortCode = $code;
    }

    /**
     * Set the pass key associated with the business short code set.
     *
     * @param string $key
     */
    public function setPassKey($key)
    {
        $this->passKey = $key;
    }

    /**
     * Set the URL which will hadle the result from the MPESA API.
     *
     * @param string $url
     */
    public function setCallbackURL($url)
    {
        $this->callbackURL = $url;
    }

    /**
     * Generate the password to be used for the transaction.
     *
     * @param string $shortCode
     * @param string $passKey
     * @param string $timestamp
     * @return string
     */
    protected function generatePassword($shortCode, $passKey, $timestamp)
    {
        return base64_encode($shortCode . $passKey . $timestamp);
    }

    /**
     * Initiate an STK push to a Safaricom mobile number.
     *
     * @param string $mobileNo
     * @param string $amount
     * @param string $description
     * @param string $accountReference
     * @param null|string $shortCode short code receiving the money
     * @return mixed
     */
    public function push($mobileNo, $amount, $description, $accountReference, $shortCode = null)
    {
        $timestamp = date('YmdHis');

        $parameters = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $this->generatePassword($this->shortCode, $this->passKey, $timestamp),
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $mobileNo,
            'PartyB' => is_null($shortCode) ? $this->shortCode : $shortCode,
            'PhoneNumber' => $mobileNo,
            'CallBackURL' => $this->callbackURL,
            'AccountReference' => $accountReference,
            'TransactionDesc' => str_limit($description, 20, ''),
        ];

        return $this->call($this->stkEndpoint, ['json' => $parameters]);
    }

    /**
     * Check the status of a Lipa Na Mpesa Online Transaction.
     *
     * @param string $checkoutRequestId
     * @param null|string $shortCode
     * @return mixed
     */
    public function transactionStatus($checkoutRequestId, $shortCode = null)
    {
        $timestamp = date('YmdHis');

        $parameters = [
            'BusinessShortCode' => is_null($shortCode) ? $this->shortCode : $shortCode,
            'Password' => $this->generatePassword($this->shortCode, $this->passKey, $timestamp),
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        return $this->call($this->statusEndPoint, ['json' => $parameters]);
    }
}


