<?php
// session_start();
// 新規登録・アカウント情報修正
require_once('../model/dao/UsersDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;

$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';

if(!empty($_GET) && !empty($_SESSION['user'])) {
    $usersDao = new UsersDao();
    $defultCompanionDao = new DefaultCompanionDao();
    $user = $usersDao->getUserById($_GET['id']);
    $companions = $defultCompanionDao->getDefaultCompanionList($user['id']);
    $title = 'アカウント情報修正';
    $mode = 'update';
    $id = $_GET['id'];
    $passChange = '';
} else {
    if(!isset($user)) {
        $user = array(
            'id' => ''
            , 'name' => ''
            , 'occupation' => '1'
            , 'sex' => '1'
            , 'email' => ''
            , 'password' => ''
            , 'remark' =>''
        );
    }
    $companions = [];
    $title = '新規登録';
    $mode = 'new';
    $id = '';
    $passChange = 'hidden';
}
if(isset($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = '';
}

include('./header.php');

include('../head.php');
include('../header.php');
include('../signUp.php');
?>
