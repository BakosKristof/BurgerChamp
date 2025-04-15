<?php

namespace Application\Assets;

class DataReader
{
    public static function initialize()
    {
        $filePath = ".env";

        if (!file_exists($filePath)) {
            die("Error: .env file not found at path: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }

    public static function ReadMySqlConnection()
    {
        $requiredKeys = ["DB_USERNAME", "DB_HOST", "DB_PASSWORD", "DB_DATABASE"];
        $datas = [];

        foreach ($requiredKeys as $key) {
            $value = getenv($key);
            if ($value === false) {
                die("Error: Environment variable '$key' not set.");
            }
            $datas[strtolower(str_replace('DB_', '', $key))] = $value;
        }
        return $datas;
    }
}
