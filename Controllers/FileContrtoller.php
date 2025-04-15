<?php

require_once "application/route/Route.php";
require_once "application/assets/Mysql/DB.php";
require_once "application/assets/Header/HttpHeadersManager.php";
require_once "application/assets/Header/HttpHeadersInterface.php";
require_once "APIAuth/Authentication.php";

use Application\Route\Route;
use Application\Database\DB;
use Application\Assets\Header\HttpHeadersInterface\HttpHeadersInterface;
use Application\Assets\Header\HttpHeadersManager\HttpHeadersManager;

Route::get("/cdn/get/{path}", [], function($params) {
    $filename =   base64_decode($params["path"]);
    $file = fopen($filename, 'rb');
    header("Content-Type: image/png");
    header("Content-Length: " . filesize($filename));
    fpassthru($file);    
});