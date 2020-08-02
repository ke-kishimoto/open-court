<?php
session_start();
require_once('../model/dao/UsersDao.php');
require_once('./header.php');
use dao\UsersDao;

$signUpDao = new UsersDao();

$user = $signUpDao->getUserByEmail($_POST['email']);

if($user) {
    if(password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
    } else {
        $errMsg = 'メールアドレス、またはパスワードが異なります';
    }
} else {
    $errMsg = 'メールアドレス、またはパスワードが異なります';
}

if(isset($errMsg)) {
    $title = 'ログイン';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/signIn.php');
} else {
    header('Location: ./index.php');
}

?>