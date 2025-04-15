<?php
namespace Application\Assets\Mysql\HelperObjects;
class JoinTables{
    public $on;
    public $table;
    public $type;

    public function __construct( $type, $table, $on){
        $this->type = $type;
        $this->table = $table;
        $this->on = $on;
    }
}

 ?>