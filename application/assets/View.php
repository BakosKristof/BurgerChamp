<?php
namespace Application\Assets;
class View{
    public static function view($path){
        include "view/".$path;
    }
}
?>
