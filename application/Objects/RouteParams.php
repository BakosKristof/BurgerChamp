<?php 
namespace Application\Objects;
class RouteParams {
    public $params;
    public $trimmedRoute;

    public function __construct($params, $trimmedRoute) {
        $this->params = $params;
        $this->trimmedRoute = $trimmedRoute;
    }
}
?>