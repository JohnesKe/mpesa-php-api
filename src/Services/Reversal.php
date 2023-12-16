<?php

namespace JohnesKe\MpesaPhpApi\Services;

use JohnesKe\MpesaPhpApi\MpesaPhpApiHttpClient;
use Illuminate\Support\Str;

class Reversal extends MpesaPhpApiHttpClient {

    /**
     * The Safaricom Reversal API end point for reversing a MPESA transaction.
     *
     * @var string
     */
    protected $reversalEndPoint = 'mpesa/reversal/v1/request';

    /**
     * The Safaricom short code initiator name.
     *
     * @var string
     */
    protected $initiatorName;

    /**
     * The encrypted initiator security credential.
     *
     * @var string
     */
    protected $securityCredential;

    /**
     * The URL where Safaricom Reversal API will send result of the transaction.
     *
     * @var string
     */
    protected $resultURL;

    /**
     * The URL where Safaricom Reversal API will send notification of the transaction
     * timing out while in the Safaricom servers queue.
     *
     * @var string
     */
    protected $queueTimeoutURL;

    /**
     * Reversal constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->validateURLsConfigurations();

        $this->initiatorName = config('mpesa-php-api.initiator.name');

        $this->securityCredential = $this->encryptedSecurityCredential(
            config('mpesa-php-api.initiator.credential'));

        $this->resultURL = config('mpesa-php-api.result_url.reversal');

        $this->queueTimeoutURL = config('mpesa-php-api.queue_timeout_url.reversal');
    }

    /**
     * Generate encrypted security credential.
     *
     * @param $plaintext
     * @return string
     * @internal param null|string $password
     */
    protected function encryptedSecurityCredential($plaintext)
    {

      $publicKey = file_get_contents(__DIR__.'\mpesa_certificate\cert.cer');

      openssl_public_encrypt($plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);

      return base64_encode($encrypted);
    }

    /**
     * Make a request to reverse a transaction to the Safaricom MPESA Reversal API.
     *
     * @param string $transactionId
     * @param string $amount
     * @param string $remarks
     * @param null|string $shortCode
     * @param string $occasion
     * @return mixed
     */
    public function reverse($transactionId, $amount, $remarks, $shortCode = null, $occasion = '')
    {

        $parameters = [
            'Initiator' => $this->initiatorName,
            'SecurityCredential' => $this->securityCredential,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transactionId,
            'Amount' => $amount,
            'ReceiverParty' => is_null($shortCode) ? 
              config('mpesa-php-api.initiator.short_code') : $shortCode,
            'RecieverIdentifierType' => '4',
            'ResultURL' => $this->resultURL,
            'QueueTimeoutURL' => $this->queueTimeoutURL,
            'Remarks' => Str::limit($remarks, 100),
            'Occasion' => Str::limit($occasion, 100),
        ];

        return $this->call($this->reversalEndPoint, ['json' => $parameters]);
    }

    /**
     * Validate URLs configurations.
     */
    protected function validateURLsConfigurations()
    {
      // Validate keys
      if (empty(config('mpesa-php-api.result_url.reversal'))) {
          throw new \InvalidArgumentException('Mpesa Reversal Result URL has not been set.');
      }

      if (empty(config('mpesa-php-api.queue_timeout_url.reversal'))) {
          throw new \InvalidArgumentException('Mpesa Reversal Queue Timeout URL has not been set');
      }

      if (empty(config('mpesa-php-api.initiator.short_code'))) {
          throw new \InvalidArgumentException('Mpesa Initiator Shortcode has not been set');
      }

      if (empty(config('mpesa-php-api.initiator.name'))) {
          throw new \InvalidArgumentException('Mpesa Initiator name has not been set');
      }

      if (empty(config('mpesa-php-api.initiator.credential'))) {
          throw new \InvalidArgumentException('Mpesa Initiator Credentials has not been set');
      }


    }
}
