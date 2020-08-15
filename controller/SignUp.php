<?php
session_start();
// 新規登録・アカウント情報修正
require_once('../model/dao/UsersDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
require_once('./header.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;

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
}

include('../view/common/head.php');
include('../view/common/header.php');
include('../view/signUp.php');
include('../view/common/footer.php');
?>
