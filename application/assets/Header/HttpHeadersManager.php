<?php

namespace Application\Assets\Header\HttpHeadersManager;

require_once "HttpHeadersInterface.php";

use Application\Assets\Header\HttpHeadersInterface\HttpHeadersInterface;

class HttpHeadersManager implements HttpHeadersInterface
{
    private static array $headers = [];

    public static function setHeader(string $header, string $value): void
    {
        self::$headers[$header] = $value;
    }

    public static function getHeader(string $header): ?string
    {
        return self::$headers[$header] ?? null;
    }

    public static function getAllHeaders(): array
    {
        return self::$headers;
    }
}
