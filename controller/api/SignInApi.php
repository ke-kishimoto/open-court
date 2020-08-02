<?php
require_once('../../model/dao/UsersDao.php');
use dao\UsersDao;

$signUpDao = new UsersDao();

if(isset($_POST)) {
    $user = $signUpDao->getUserByEmail($_POST['email']);
}

if(isset($user)) {
    if(password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        $user['result'] = 'OK';
    } else {
        $user['result'] = 'NG';
    }
} else {
    $user['result'] = 'NG';
}

echo json_encode($user);

?>