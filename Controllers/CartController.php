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
Route::post("/cart", ["id","img","name","quantity", "price",], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $id=$params["id"];
    $img=$params["img"];
    $quantity=$params["quantity"];
    $description=$params["description"];
    $price=$params["price"];
    

    DB::table('cart')->insert([
        ['img'=>$img,'name' => $name, 'quantity' => $quantity, 'price'=>$price ],
       
    ]);

});
