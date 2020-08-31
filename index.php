<?php 
require_once('./controller/UserController.php');
require_once('./controller/InquiryController.php');
require_once('./controller/ParticipantController.php');
require_once('./controller/admin/AdminController.php');
require_once('./controller/admin/ConfigController.php');
require_once('./controller/admin/EventController.php');
require_once('./controller/admin/ParticipantController.php');
require_once('./controller/api/EventApi.php');
// include('./controller/index.php');
// var_dump('test');

use controller\UserController;

// if(strlen($_SERVER['REQUEST_URI']) > 0) {
//     $url = substr($_SERVER['REQUEST_URI'], 1, -1);
// }
$url = explode("/", $_SERVER['REQUEST_URI']);
// 空白削除
// $url = array_filter( $url, "strlen" ) ;

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
// require_once('./controller/header.php');
   include('./controller/index.php');
}



// if (strpos($_SERVER['REQUEST_URI'], 'user') > 0) {
//     $userController = new UserController();
//     $userController->signIn();
// } else {
//     include('./controller/index.php');
// }


