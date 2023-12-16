<?php
namespace JohnesKe\MpesaPhpApi\Api;

use JohnesKe\MpesaPhpApi\Helpers\Config;
use JohnesKe\MpesaPhpApi\Helpers\Network;

class Stk
{
    use Network,Config;

    private $stkUrl = '/mpesa/stkpush/v1/processrequest';
    private $stkQuery = '/mpesa/stkpushquery/v1/query';

    public function stkPushRequest($amount,$partyA,$partyB,$phoneNumber,$callBackUrl,
                                    $accountReference,$transactionDescription){
       
        $timestamp = date("Ymdhis");
        $password  = base64_encode(Config::$shortCode.Config::$passKey.$timestamp);
        
        $Data = array(
            'BusinessShortCode' => Config::$shortCode,
            'Password'         => $password,
            'Timestamp'        => $timestamp,
            'TransactionType'  => "CustomerPayBillOnline",
            'Amount'           => $amount,
            'PartyA'           => $partyA,
            'PartyB'           => $partyB,
            'PhoneNumber'      => $phoneNumber,
            'CallBackURL'      => $callBackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc'  => $transactionDescription
        );

        $response = Network::postRequest($this->stkUrl,json_encode($Data));

        return json_decode($response);
    }

    public function stkPushQuery($checkoutRequestID){

        $timestamp = date("Ymdhis");
        $password  = base64_encode(Config::$shortCode.Config::$passKey.$timestamp);
        
        $Data = array(
            'BusinessShortCode' => Config::$shortCode,
            'Password'         => $password,
            'Timestamp'        => $timestamp,
            'CheckoutRequestID'  => $checkoutRequestID
        );

        $response = Network::postRequest($this->stkQuery,json_encode($Data));

        return json_decode($response);
    }
}

?>