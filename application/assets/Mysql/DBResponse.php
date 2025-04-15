<?php 
namespace Application\Assets\Mysql;

use Stringable;

class DBResponse {
    public $json;
    public $phpArray;

    public function __construct($json, $phpArray){
        $this->json = $json;
        $this->phpArray = $phpArray;
    }
    public function Count() : int{
        return count($this->phpArray);
    }
    public function Avg(String $col) : float {
        $avg = 0;
        foreach($this->phpArray as $i){
            $avg+=  intval($i[$col]);
        }
        return $avg/count($this->phpArray);
    }

}
?>