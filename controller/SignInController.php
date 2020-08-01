<?php
session_start();

require_once('../model/dao/UsersDao.php');
use dao\UsersDao;

$signUpDao = new UsersDao();

$user = $signUpDao->getUserByEmail($_POST['email']);

if($user) {
    if(password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: ./index.php');
    } else {
        $_SESSION['errMsg'] = 'メールアドレス、またはパスワードが異なります';
        include('../signIn.php');
    }
} else {
    $_SESSION['errMsg'] = 'メールアドレス、またはパスワードが異なります';
    include('../view/signIn.php');
}
?>