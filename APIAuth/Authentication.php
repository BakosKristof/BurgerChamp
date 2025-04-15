<?php
class Authentication
{
    public static function Authenticate($clientToken): bool
    {
        $token = getenv("TOKEN");
        return trim($clientToken) == trim($token);
    }
    public static function init()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, token");
            header("Content-Length: 0");
            header("Content-Type: text/plain");
            die();
        }
    }
}
