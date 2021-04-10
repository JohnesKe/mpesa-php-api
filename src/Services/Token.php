<?php
namespace JohnesKe\MpesaPhpApi\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;// for 400-level errors
use GuzzleHttp\Exception\ServerException;// for 500-level errors
use GuzzleHttp\Exception\BadResponseException;// for both (it's their superclass)

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
            $response = $this->client->get($this->token_url);
            $resp = $response->getBody()->getContents();

            //convert json to php objects
            $result_1 = json_decode($resp);

            return $Token = $result_1->access_token; 

        } catch (ClientException | ServerException | BadResponseException $e) {
            // recover + log with monolog
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());

        } catch(\Exception $e){
          // recover + slightly different log with monolog
          echo Psr7\str($e->getRequest());
          echo Psr7\str($e->getResponse());
        }    
        
    }

}
