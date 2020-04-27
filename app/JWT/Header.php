<?php


namespace App\JWT;

class Header
{
    public static $type = 'JWT';

    public static $alg = 'HS256';

    public static function getType()
    {
        return self::$type;
    }

    public static function getAlg()
    {
        return self::$alg;
    }

    public static function toJson()
    {
        return \json_encode([
            'type' => self::getType(),
            'alg' => self::getAlg()
        ]);
    }
}
