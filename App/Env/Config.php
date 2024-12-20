<?php

namespace App\Env;

use Symphograph\Bicycle\Env\Env;

class Config extends \Symphograph\Bicycle\Env\Config
{

    public static function initEndPoints(): void
    {
        self::checkOrigin();

        self::initEndPoint(
            '/epoint/',
            ['POST', 'OPTIONS'],
            ['HTTP_ACCESSTOKEN' => '']
        );

        self::initEndPoint(
            '/curl/',
            ['GET' ,'POST', 'OPTIONS'],
            ['HTTP_AUTHORIZATION' => Env::getApiKey()]
        );
    }


}