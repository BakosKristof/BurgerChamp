<?php

namespace Application\Assets\Mysql;

require_once "DataReader.php";

use  Application\Assets\DataReader;
use PDO;
use PDOException;

class Connection
{
    public static function TryConnect(): PDO
    {
        DataReader::initialize();
        $mysql_login = DataReader::ReadMySqlConnection();

        $dsn = 'mysql:host='.$mysql_login["host"].';dbname='.$mysql_login["database"].';charset=utf8';
        $felhasznalonev = $mysql_login["username"];
        $jelszo = $mysql_login["password"];
        $pdo = null;
        try {
            $pdo = new PDO($dsn, $felhasznalonev, $jelszo);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo 'Kapcsolódási hiba: ' . $e->getMessage();
            exit;
        }
        return $pdo;
       
    }
}
