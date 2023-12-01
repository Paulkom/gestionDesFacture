<?php


namespace App\Services\lib\Model;

class PaymentTypeEnum
{
    /**
     * Possible values of this enum
     */
    const ESPECES = 'ESPECES';
    const VIREMENT = 'VIREMENT';
    const CARTEBANCAIRE = 'CARTEBANCAIRE';
    const MOBILEMONEY = 'MOBILEMONEY';
    const CHEQUES = 'CHEQUES';
    const CREDIT = 'CREDIT';
    const AUTRE = 'AUTRE';
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::ESPECES,
            self::VIREMENT,
            self::CARTEBANCAIRE,
            self::MOBILEMONEY,
            self::CHEQUES,
            self::CREDIT,
            self::AUTRE,        
        ];
    }
}
