<?php
namespace JohnesKe\MpesaPhpApi;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class Token {

    protected $client;

    protected $token_url;

    public function __construct($token_client, $token_url)
    {
        $this->client      = $token_client;
        $this->token_url   = $token_url;
    
    }

    public function getToken(){

        try {
            $response = $this->client->request('GET', $this->token_url);

            $resp = $response->getBody()->getContents();

            //convert json to php objects
            $result_1 = json_decode($resp);

            return $result_1->access_token;

        } catch (ClientException $e) {
            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());
        }
    }

}

?>
