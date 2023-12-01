<?php

namespace App\Services\lib\Model;

class TaxGroupTypeEnum
{
    /**
     * Possible values of this enum
     */
    const A = 'A';
const B = 'B';
const C = 'C';
const D = 'D';
const E = 'E';
const F = 'F';
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::A,
self::B,
self::C,
self::D,
self::E,
self::F,        ];
    }
}
