<?php
namespace JohnesKe\MpesaPhpApi\Helpers;

trait Network
{

    use Config;
	
    public function postRequest($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::$baseDomain.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.Config::$credentials, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response     = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}

?>

