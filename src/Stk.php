<?php
namespace JohnesKe\MpesaPhpApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;      // for 400-level errors
use GuzzleHttp\Exception\ServerException;      // for 500-level errors
use GuzzleHttp\Exception\BadResponseException; // for both (it's their superclass)

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

}

?>