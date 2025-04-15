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

Route::post("/user/register", ["name","email", "password",], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $name=$params["name"];
    $email=$params["email"];
    $password=password_hash($params["password"],PASSWORD_DEFAULT);
    
    $res = DB::runSql("select * from users where email like '$email'"); 
    if((count($res) > 0)){
        http_response_code(401);
        return DB::arrayToJson(["error" => "Az email már használatban van!", "success" => false, "user" => null]);
    } 

    DB::table('users')->insert([
        ['name' => $name, 'email' => $email, 'password'=>$password ],
       
    ]);

    http_response_code(200);
    return DB::arrayToJson(["error" => "", "success" => true, "user" => null]);
});

Route::post("/user/login", ["email", "password",], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $email=$params["email"];
    $password=$params["password"];

    $res = DB::runSql("select * from users where email like '$email'");
    if(!(count($res) > 0)){
        http_response_code(401);
        return DB::arrayToJson(["error" => "A bejelentkezés sikertelen!", "success" => false, "user" => null]);
    } else {
        $user = $res[0];
        if(password_verify($password, $user->password)){
            http_response_code(200);
            return DB::arrayToJson(["error" => "", "success" => true, "user" => $user]);
        }
    }
    http_response_code(401);
    return DB::arrayToJson(["error" => "A bejelentkezés sikertelen!", "success" => false, "user" => null]); 
});

Route::get("/user/profil/{id}", ["id"], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $id = $params["id"];
    return DB::arrayToJson(DB::runSql("select * from users where id = $id")); 
}, Authentication::class);
