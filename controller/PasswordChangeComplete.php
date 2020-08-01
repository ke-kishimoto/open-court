<?php
require_once('../model/dao/UsersDao.php');
use dao\UsersDao;

if (!empty($_POST)) {
    $errMsg = '';
    $usersDao = new UsersDao();

    //パスワードチェック
    if (($_POST['password']) != ($_POST['rePassword'])) {
        $errMsg = 'パスワード(再入力)が同じでありません';
    }

    if(empty($errMsg)){
        $usersDao->updatePass($_SESSION['user']['id'], password_hash($_POST['password'], PASSWORD_DEFAULT));
    }
}
$title = 'パスワード変更完了';
$msg = 'パスワードを変更しました';
include('./header.php');

include('../view/head.php');
include('../view/header.php');
include('../view/complete.php');
?>