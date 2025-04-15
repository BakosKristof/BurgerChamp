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

Route::post("/reviews", ["user_id", "comment"], function ($params) {
    $user_id=$params["user_id"];
    $comment=$params["comment"]; 
    
    //Milán:működik           Milán működik?
  try {
       if($comment!=""){
        DB::table('reviews')->insert([
            ['user_id' => $user_id,  'comment' => $comment],
        ]);
        echo json_encode(["success" => true, "message" => "Sikeres feltöltés"]);
       }
       
        
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }



});


