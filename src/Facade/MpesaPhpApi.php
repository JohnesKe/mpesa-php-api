<?php

namespace JohnesKe\MpesaPhpApi\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class MpesaPhpApi
 * @package JohnesKe\MpesaPhpApi\Facade
 */
class MpesaPhpApi extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'MpesaPhpApi';
    }


}