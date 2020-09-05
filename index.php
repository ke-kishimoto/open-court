<?php 
// require_once('./controller/UserController.php');
// require_once('./controller/EventController.php');
// require_once('./controller/InquiryController.php');
// require_once('./controller/ParticipantController.php');
// require_once('./controller/admin/AdminController.php');
// require_once('./controller/admin/ConfigController.php');
// require_once('./controller/admin/EventController.php');
// require_once('./controller/admin/ParticipantController.php');
// require_once('./controller/api/EventApi.php');

use controller\EventController;

spl_autoload_register(function($class) {
    $pathArray = explode("\\", $class);
    $path = implode("/", $pathArray);
    require($path .'.php');
});

$url = explode("/", $_SERVER['REQUEST_URI']);
if(count($url) > 2 && strlen($url[1]) > 0 && strlen($url[2])) {

    if($url[1] === 'admin') {
        $controllerName = 'controller\\' . $url[1] . '\\' . strtoupper(substr($url[2], 0, 1)) . substr($url[2], 1) . 'Controller';
        $req = explode("?", $url[3]);
    } elseif ($url[1] === 'api') {
        $controllerName = 'controller\\' . $url[1] . '\\' . strtoupper(substr($url[2], 0, 1)) . substr($url[2], 1) . 'Api';
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
