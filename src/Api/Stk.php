<?php
namespace JohnesKe\MpesaPhpApi\Api;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class Stk
{

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

            //$response = $this->client->request('GET', $this->token_url);

            $response = $this->stkClient->post( $this->stkUrl, ['body' => $data_string ] );

            $resp = $response->getBody()->getContents();

            return $resp; 

        } catch (ClientException $e) {
            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());
        }    
        
    }

}

?>