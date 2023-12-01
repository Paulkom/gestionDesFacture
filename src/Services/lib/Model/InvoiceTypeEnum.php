<?php

namespace App\Services\lib\Model;

class InvoiceTypeEnum
{
    /**
     * Possible values of this enum
     */
    const FV = 'FV';
    const FA = 'FA';
    const EV = 'EV';
    const EA = 'EA';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    
    public static function getAllowableEnumValues()
    {
        return [
            self::FV,
            self::FA,
            self::EV,
            self::EA,         
        ];
    }
}
