<?php

namespace Application\Objects;

class RouteStruct
{
    public $path;
    public $callback;
    public $requreParams;
    public $routeMethod;
    public $authClass;
    public function __construct($path, $requreParams, $callback, $routeMethod, $authClass)
    {
        $this->path = $path;
        $this->callback = $callback;
        $this->routeMethod = $routeMethod;
        $this->requreParams = $requreParams;
        $this->authClass = $authClass;
    }
}
