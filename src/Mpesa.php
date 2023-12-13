<?php
namespace JohnesKe\MpesaPhpApi;

use GuzzleHttp\Client;
use JohnesKe\MpesaPhpApi\Api\Stk;

class Mpesa
{
    const BASE_DOMAIN            = "safaricom.co.ke";
    const BASE_SANDBOX_DOMAIN    = "https://sandbox." . self::BASE_DOMAIN;
    const BASE_PRODUCTION_DOMAIN = "https://api." . self::BASE_DOMAIN;

    protected $tokenUrl = '/oauth/v1/generate?grant_type=client_credentials';

    protected $stkUrl   = '/mpesa/stkpush/v1/processrequest';

    protected $baseDomain;

    protected $tokenClient;

    function __construct( $environment, $consumer_key , $consumer_secret ){

       if($environment === 'sandbox') {
            $this->baseDomain = self::BASE_SANDBOX_DOMAIN;

        } elseif($environment === 'live') {
            $this->baseDomain = self::BASE_PRODUCTION_DOMAIN;
        }

        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        
        $this->tokenClient = new Client([
            'base_uri' => $this->baseDomain,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic '.$credentials
            ]
        ]);

    }

    // Get Token
    public function token()
    {
        $token = new Token($this->tokenClient, $this->tokenUrl);
        return $token->getToken();
    }

    // STK Request
    public function stkRequest( $token,$BusinessShortCode,$LipaNaMpesaPassKey,
                                $TransactionType,$Amount,$PartyA,$PartyB,
                                $PhoneNumber,$CallBackUrl,$AccountReference,
                                $TransactionDescription,$Remarks ){

        $stkClient = new Client([
            'base_uri' => $this->baseDomain,
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ]
        ]);

        $stkResponse = new Stk( $stkClient,$this->stkUrl,$BusinessShortCode,
                                $LipaNaMpesaPassKey,$TransactionType,
                                $Amount,$PartyA,$PartyB,$PhoneNumber,
                                $CallBackUrl,$AccountReference,
                                $TransactionDescription,$Remarks
                            );

        return $stkResponse->stkPushRequest();
    }

}
