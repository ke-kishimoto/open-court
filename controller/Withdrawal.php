<?php
require_once('../model/dao/UsersDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
require_once('./header.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;

if(isset($_SESSION['user'])) {

    $usersDao = new UsersDao();
    try {
        // トランザクション開始
        $usersDao->getPdo()->beginTransaction();
        $defaultCompanionDao = new DefaultCompanionDao();
        // 同伴者の削除
        $defaultCompanionDao->deleteByuserId($_SESSION['user']['id']);
        $usersDao->delete($_SESSION['user']['id']);
    
        $usersDao->getPdo()->commit();
    
        // session_unset('user');
        session_destroy();
    
    } catch(Exception $ex) {
        $usersDao->getPdo()->rollBack();
    }
}
$title = '退会';
$msg = '退会処理が完了しました。';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/complete.php');

?>

