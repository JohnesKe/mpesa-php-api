<?php

namespace JohnesKe\MpesaPhpApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Route;

use JohnesKe\MpesaPhpApi\Exceptions\MpesaPhpApiRequestException;
use JohnesKe\MpesaPhpApi\Logging\Log;

class MpesaPhpApiHttpClient
{

    /**
     * Guzzle client initialization.
     *
     * @var Client
     */
    protected $client;

    /**
     * Safaricom MPESA APIs application consumer key.
     *
     * @var string
     */
    protected $consumerKey;

    /**
     * Safaricom MPESA APIs application consumer secret.
     *
     * @var string
     */
    protected $consumerSecret;

    /**
     * Identifier organization Map on Safaricom MPESA APIs.
     *
     * @var array
     */
    protected $identifier = [
        'msisdn' => '1', // MSISDN
        'till' => '2', // Till Number
        'paybill' => '4', // Shortcode
    ];

    /**
     * Base URL end points for the Safaricom APIs.
     *
     * @var array
     */
    protected $base_url = [
        'sandbox' => 'https://sandbox.safaricom.co.ke',
        'live' => 'https://api.safaricom.co.ke',
    ];


    /**
     * Make the initializations required to make calls to the Safaricom MPESA APIs
     * and throw the necessary exception if there are any missing required
     * configurations.
     */
    function __construct(){

      $this->validateConfigurations();

      $mode = config('mpesa-php-api.mpesa_environment');

      $options = [
          'base_uri' => $this->base_url[$mode],
          'verify' => $mode === 'sandbox' ? false : true,
      ];

      if (config('mpesa-php-api.logs.enabled')) {
          $options = Log::enable($options);
      }

      $this->client = new Client($options);

      $this->consumerKey = config('mpesa-php-api.consumer_key');
      $this->consumerSecret = config('mpesa-php-api.consumer_secret');
        
    }

    protected function generateToken(){

      // Set the auth option
      $options = [
          'auth' => [
              $this->consumerKey,
              $this->consumerSecret,
          ],
      ];

      $method  = 'GET';

      $url     = 'oauth/v1/generate?grant_type=client_credentials';

      try {

        $response = $this->client->request($method, $url, $options);

        $stream = $response->getBody();
        $stream->rewind();
        $content = $stream->getContents();

        $accessTokenDetails = json_decode($content);

        return $accessTokenDetails->access_token;

      } catch (ServerException $e) {

        $response = json_decode($e->getResponse()->getBody()->getContents());

        if (isset($response->Envelope)) {
            $message = 'Mpesa Php APIs: '.$response->Envelope->Body->Fault->faultstring;
            throw new MpesaPhpApiRequestException($message, $e->getCode());
        }

        

        throw new MpesaPhpApiRequestException(
          'Mpesa Php APIs: '.$response->errorMessage, $e->getCode());

      } catch (ClientException $e) {

        $response = json_decode($e->getResponse()->getBody()->getContents());

        //dd($response);

        throw new MpesaPhpApiRequestException(
          'Mpesa Php APIs: '.$response->errorMessage, $e->getCode());
      }

    }

    /**
     * Validate configurations.
     */
    protected function validateConfigurations()
    {
      // Validate keys
      if (empty(config('mpesa-php-api.consumer_key'))) {
          throw new \InvalidArgumentException('Consumer key has not been set.');
      }
      if (empty(config('mpesa-php-api.consumer_secret'))) {
          throw new \InvalidArgumentException('Consumer secret has not been set');
      }
    }

    /**
     * Make API calls to Safaricom MPESA APIs.
     *
     * @param string $url
     * @param array $options
     * @param string $method
     * @return mixed
     * @throws MpesaPhpApiRequestException
     */
    protected function call($url, $options = [], $method = 'POST')
    {
        
      $options['headers'] = ['Authorization' => 'Bearer '.$this->generateToken()];
      
      try {

        $response = $this->client->request($method, $url, $options);

        $stream = $response->getBody();
        $stream->rewind();
        $content = $stream->getContents();

        return json_decode($content);

      } catch (ServerException $e) {

        $response = json_decode($e->getResponse()->getBody()->getContents());

        if (isset($response->Envelope)) {
          $message = 'Mpesa Php APIs: '.$response->Envelope->Body->Fault->faultstring;
          throw new MpesaPhpApiRequestException($message, $e->getCode());
        }

        //dd($response);

        throw new MpesaPhpApiRequestException(
          'Mpesa Php APIs: '.$response->errorMessage, $e->getCode());

      } catch (ClientException $e) {

        $response = json_decode($e->getResponse()->getBody()->getContents());

        //dd($response);

        throw new MpesaPhpApiRequestException(
          'Mpesa Php APIs: '.$response->errorMessage, $e->getCode());
      }
    }

}
