<?php

namespace Application\Assets\Header\HttpHeadersInterface;

interface HttpHeadersInterface
{

    public const HEADER_CONTENT_TYPE = 'Content-Type';
    public const HEADER_CONTENT_LENGTH = 'Content-Length';
    public const HEADER_CONTENT_ENCODING = 'Content-Encoding';
    public const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    public const HEADER_AUTHORIZATION = 'Authorization';
    public const HEADER_WWW_AUTHENTICATE = 'WWW-Authenticate';
    public const HEADER_ACCEPT = 'Accept';
    public const HEADER_ACCEPT_ENCODING = 'Accept-Encoding';
    public const HEADER_USER_AGENT = 'User-Agent';
    public const HEADER_HOST = 'Host';
    public const HEADER_CONNECTION = 'Connection';


    public const HEADER_CACHE_CONTROL = 'Cache-Control';
    public const HEADER_EXPIRES = 'Expires';
    public const HEADER_ETAG = 'ETag';
    public const HEADER_LOCATION = 'Location';
    public const HEADER_SET_COOKIE = 'Set-Cookie';

    public static function setHeader(string $header, string $value): void;
    public static function getHeader(string $header): ?string;
    public static function getAllHeaders(): array;
}
