<?php 
use controller\EventController;

spl_autoload_register(function($class) {
    $pathArray = explode("\\", $class);
    $path = implode("/", $pathArray);
    if ($pathArray[0] === 'controller' || $pathArray[0] === 'service') {
        require($path .'.php');
    } elseif ($pathArray[0] === 'api') {
        require('controller/' . $path .'.php');
    } elseif ($pathArray[0] === 'dao') {
        require('model/' . $path .'.php');
    }
});

$url = explode("/", $_SERVER['REQUEST_URI']);
if(count($url) > 2 && strlen($url[1]) > 0 && strlen($url[2])) {

    // if($url[1] === 'admin') {
    //     $controllerName = 'controller\\' . $url[1] . '\\' . strtoupper(substr($url[2], 0, 1)) . substr($url[2], 1) . 'Controller';
    //     $req = explode("?", $url[3]);
    // } else
    if ($url[1] === 'api') {
        // $controllerName = 'controller\\' . $url[1] . '\\' . strtoupper(substr($url[2], 0, 1)) . substr($url[2], 1) . 'Api';
        $controllerName = $url[1] . '\\' . strtoupper(substr($url[2], 0, 1)) . substr($url[2], 1) . 'Api';
        $req = explode("?", $url[3]);
    } else {
        $controllerName = 'controller\\' . strtoupper(substr($url[1], 0, 1)) . substr($url[1], 1) . 'Controller';
        $req = explode("?", $url[2]);
    }
    
    
    $rClass = new ReflectionClass($controllerName);
    $controller = $rClass->newInstance();
    $method = $rClass->getMethod($req[0]);
    $method->invoke($controller);
} else {
    $controller = new EventController;
    $controller->index();
}
