<?php
require_once('../model/dao/UsersDao.php');
require_once('./header.php');
use dao\UsersDao;

if (!empty($_POST)) {
    $errMsg = '';
    $usersDao = new UsersDao();

    //パスワードチェック
    if (($_POST['password']) != ($_POST['rePassword'])) {
        $errMsg = '入力されたパスワードが異なります。';
    }

    if(empty($errMsg)){
        $usersDao->updatePass($_SESSION['user']['id'], password_hash($_POST['password'], PASSWORD_DEFAULT));
        $title = 'パスワード変更完了';
        $msg = 'パスワードを変更しました';
        
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/complete.php');
    } else {
        $title = 'パスワード変更';
        
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/passwordChange.php');
    }
}
?>