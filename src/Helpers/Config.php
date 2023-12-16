<?php
namespace JohnesKe\MpesaPhpApi\Helpers;

trait Config
{
    private $sandbox_domain  = "https://sandbox.safaricom.co.ke";

    private $production_domain = "https://api.safaricom.co.ke";

    public $baseDomain;
    
    public $credentials;

    public $shortCode;

    public $passKey;

    public function init( 
                            $environment, 
                            $consumer_key,
                            $consumer_secret,
                            $businessShortCode,
                            $lipaNaMpesaPassKey
                        ){

       if($environment === 'sandbox') {
            $this->baseDomain = $this->sandbox_domain;
            $this->credentials = base64_encode($consumer_key.':'.$consumer_secret);
            $this->passkey = $lipaNaMpesaPassKey;
            $this->shortCode = $businessShortCode;
        } elseif($environment === 'live') {
            $this->baseDomain = $this->production_domain;
            $this->credentials = base64_encode($consumer_key.':'.$consumer_secret);
            $this->passkey = $lipaNaMpesaPassKey;
            $this->shortCode = $businessShortCode;
        }
    }

}