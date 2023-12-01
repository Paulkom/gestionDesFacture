<?php

namespace App\Database;

use Doctrine\ORM\EntityManagerInterface;

class NativeQueryMySQL
{
    protected static $conn;
    public function __construct(EntityManagerInterface $em)
    {
        self::$conn = $em->getConnection();
    }
    public static function getConnection()
    {
        return self::$conn;
    }

    public static function GetAllColumnsFromTable($tablename)
    {
        $sql = "SHOW COLUMNS FROM ".$tablename;
        $rs = self::$conn->query($sql);
        return $rs->fetchAll();
    }
}