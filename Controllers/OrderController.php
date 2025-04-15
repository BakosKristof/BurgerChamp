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

Route::post("/Order", ["user_id","order_details"], function ($params) {
    
    $user_id=$params["user_id"];
    $order_details=json_decode($params["order_details"]);
    $total_amount=0;
    $levonando_points = 0;
    $points = 0;



    $Order=[];
    
    foreach($order_details as $i){
        if(isset($i->item->point_reqiued)){
            $levonando_points+=$i->item->point_reqiued;
        }
       if(isset($i->item->points)){
        $points+=$i->item->points;
       }
       if(isset($i->item->price)){
        $total_amount+= intval($i->item->price)*intval($i->quantity);
    } 
}
       
      



    $Order=[];
    foreach($order_details as $i){
        if(isset($i->price)){
            $total_amount+=$i->price;
        }
      
    }
    
    $oid=DB::table('orders')->insert([
        ['user_id' => $user_id,  'total_amount' => $total_amount],
    ])->getLastInsertId();

 
    foreach($order_details as $i){
        
        $Order[] = ["order_id"=> intval($oid), "menu_item_id"=> intval($i->item->id), "quantity"=> $i->quantity, "price"=>  intval($i->item->price)];

        $points += intval($i->loyalty_points)* intval($i->quantity);
        
    }
    DB::table("order_details")->insert($Order);

    if($levonando_points > 0){
        $res= DB::runSql("UPDATE users 
        SET points = (SELECT points FROM users WHERE id = '$user_id') - '$levonando_points'
        WHERE id = '$user_id';"); 
        }
         $res= DB::runSql("UPDATE users 
SET points = (SELECT points FROM users WHERE id = '$user_id') + '$points'
WHERE id = '$user_id';"); 
 

 
   return "
   setCookie('kosar', '[]', 7);
  var modal = new Modal();
      modal.create('Értesítés', 'Sikeres rendelés, átvehető a pultnál!').open();
      window.location.reload();
   ";


});



/*SELECT orders.*, (SELECT JSON_ARRAYAGG( JSON_OBJECT( 'id', order_details.id, 'menu_name', menu_items.name ) ) FROM order_details INNER JOIN menu_items ON menu_items.id = order_details.menu_item_id WHERE order_details.order_id = orders.id ) AS order_details FROM orders; */


