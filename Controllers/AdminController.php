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

Route::get("/admin/user/{page}/get", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $page = $params["page"];
    $users = DB::runSql("SELECT * FROM `users` ORDER BY id");
    return DB::arrayToJson(["users" => $users]); 
});

Route::post("/admin/user/insert", ["name","email", "password"], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $name=$params["name"];
    $email=$params["email"];
    $password=password_hash($params["password"],PASSWORD_DEFAULT);

    DB::table('users')->insert([
        ['name' => $name, 'email' => $email, 'password'=>$password ],
       
    ]);
    return DB::arrayToJson(["success" => true]);
});


Route::get("/admin/user/delete/{id}", ["id"], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $id = $params["id"];
    DB::runSql("delete from users where id = $id");

    return DB::arrayToJson(["success" => true]);   
});



Route::post("/admin/user/update/{id}", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $id = $params["id"];
    $name=$params["name"];
    $email=$params["email"];
    $points = $params["points"];
    $password=password_hash($params["password"],PASSWORD_DEFAULT);

    DB::table('users')
    ->update(['name' => $name, 'email'=>$email, 'password'=>$password, "points" => $points])
    ->where('id', '=', $id)
    ->save();
       
    
});




Route::get("/admin/order/get", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $res = DB::runSql("SELECT orders.*, users.name, users.email, (SELECT JSON_ARRAYAGG( JSON_OBJECT( 'id', order_details.id, 'menu_name', menu_items.name ) ) FROM order_details INNER JOIN menu_items ON menu_items.id = order_details.menu_item_id WHERE order_details.order_id = orders.id ) AS order_details FROM orders INNER JOIN users ON users.id = orders.user_id");

    $orders = [];
    foreach($res as $i){
        $orders[] = ["id" => $i->id,  "user_id" => $i->user_id,  "order_date" => $i->order_date,  "total_amount" => $i->total_amount, 
        "status" => $i->status, "name" => $i->name, "email" => $i->email, "order_details" => json_decode($i->order_details)];
    } 
    echo DB::arrayToJson($orders);
  
});



Route::post("/admin/order/add", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    
    try {
        $requiredFields = ['order_id', 'menu_item_id', 'quantity', 'price'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field])){
                throw new Exception("Hiányzó kötelező mező: " . $field);
            }
        }
        $orderId = filter_var($params['order_id'], FILTER_VALIDATE_INT);
        $menuItemId = filter_var($params['menu_item_id'], FILTER_VALIDATE_INT);
        $quantity = filter_var($params['quantity'], FILTER_VALIDATE_INT);
        $price = filter_var($params['price'], FILTER_VALIDATE_INT);

        if ($orderId === false || $menuItemId === false || $quantity === false || $price === false) {
            throw new Exception("Érvénytelen adatformátum");
        }

        $insertedId = DB::table('order_details')->insert([
            'order_id' => $orderId,
            'menu_item_id' => $menuItemId,
            'quantity' => $quantity,
            'price' => $price
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Rendelési tétel sikeresen hozzáadva',
            'inserted_id' => $insertedId
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Hiba: ' . $e->getMessage()
        ]);
    }
});


Route::get("/admin/order/del/{id}", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    
    $id = $params["id"];
    
    try {
      
        DB::table('order_details')
            ->where('order_id', '=', $id)
            ->remove();
       
        $deleted = DB::table('orders')
            ->where('id', '=', $id)
            ->remove();
        
    } catch (Exception $e) {
        
    }
});



Route::post("/admin/order/update", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $user_id=$params["user_id"];
    $order_details=json_decode($params["order_details"]);


    DB::table('orders')

    ->where('id', '=', $id)
    ->save();
       
    
});








Route::get("/admin/reviews", [], function ($params) {
    $reviews = DB::runSql("SELECT * FROM `reviews`  ORDER BY id asc limit 0,10");
    return DB::arrayToJson(["reviews" => $reviews]);
});


Route::get("/admin/reviews/del/{id}", [], function ($params) {
    HttpHeadersManager::setHeader(HttpHeadersInterface::HEADER_CONTENT_TYPE, 'application/json; charset=utf-8');
    $id=$params["id"];
    $user_id=$params["user_id"];
    $menu_item_id=$params["menu_item_id"];
    $comment=$params["comment"];

    DB::table('reviews')
    ->where('id', '=', $id)
    ->remove();
});



Route::get("/admin/menu", [], function ($params) {
    $menus = DB::runSql("SELECT * FROM `menu_items`  ORDER BY id asc limit 0,10");
    return DB::arrayToJson(["menus" => $menus]);
});

Route::get("/admin/loyalty_rewards", [], function ($params) {
    $loyalty_rewards= DB::runSql("SELECT * FROM `loyalty_rewards`  ORDER BY id asc limit 0,10");
    return DB::arrayToJson(["loyalty_rewards" => $loyalty_rewards]);
});


Route::post("admin/update-status/{orderid}", ["orderid", "status"], function($params){
    $orderid = $params["orderid"];
    $status = $params["status"];
    DB::table('orders')
    ->update(["status" => $status])
    ->where('id', '=', $orderid)
    ->save();

    return DB::arrayToJson(["success" => true]);

});



