<?php


namespace App\Services\lib\Model;

class AibGroupTypeEnum
{
    /**
     * Possible values of this enum
     */
    const A = 'A';
const B = 'B';
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::A,
            self::B,        
        ];
    }
}
