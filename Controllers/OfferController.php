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

Route::get("/Offer/get", [], function ($params) {
  HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
  $offers= DB::runSql("SELECT loyalty_rewards.id, loyalty_rewards.point_reqiued, loyalty_rewards.menu_id, menu_items.name, menu_items.description, menu_items.images FROM `loyalty_rewards` INNER JOIN menu_items ON loyalty_rewards.menu_id = menu_items.id;");
  return DB::arrayToJson($offers);
  });


Route::post("/Offer", ["id","reward_name","points_required","description"], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
  $id=$params["id"];
  $reward_name=$params["reward_name"];
  $points_required=$params["points_required"];
  $description=$params["description"];
  $points=0;




  //SELECT users.name, (users.points- loyalty_rewards.points_required)as "Pont" FROM `loyalty_rewards` inner JOIN users WHERE loyalty_rewards.id =1;




  });

