<?php
namespace JohnesKe\MpesaPhpApi\Api;

use JohnesKe\MpesaPhpApi\Helpers\Config;
use JohnesKe\MpesaPhpApi\Helpers\Network;

class C2b {

    use Config, Network;

    private $c2b_paybill_online_url = '/mpesa/c2b/v1/simulate';

    public function c2b_paybill_online($amount,$phoneNumber,$billRefNumber){

        $data = array(
            'ShortCode'     => Config::$shortCode,
            'CommandID'     => "CustomerPayBillOnline",
            'amount'        => $amount,
            'MSISDN'        => $phoneNumber,
            'BillRefNumber' => $billRefNumber
        );

        $response = Network::postRequest($this->c2b_paybill_online_url,json_encode($data));

        return json_decode($response);
    }

}
