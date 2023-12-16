<?php
namespace JohnesKe\MpesaPhpApi\Api;

use JohnesKe\MpesaPhpApi\Helpers\Config;

class Token
{
    use Config;

    private $tokenUrl = "/oauth/v1/generate?grant_type=client_credentials";

    public function __construct($environment,$consumer_key,$consumer_secret,$businessShortCode,$lipaNaMpesaPassKey)
    {
        Config::init($environment,$consumer_key,$consumer_secret,$businessShortCode,$lipaNaMpesaPassKey);
    }

    // Get Token
    public function getToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::$baseDomain.$this->tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.Config::$credentials, 'Content-Type: application/json'));
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
}
