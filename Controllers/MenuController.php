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

Route::get("/Menu/get", [], function ($params) {
  HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
  $menu_categories = DB::runSql("SELECT menu_items.category FROM `menu_items` GROUP by category ORDER BY menu_items.category asc");
  $menus = DB::runSql("SELECT * FROM `menu_items` ORDER BY menu_items.name asc");
  return DB::arrayToJson(["categories" => $menu_categories, "menus" => $menus]);
});
