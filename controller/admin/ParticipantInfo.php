<?php
session_start();
require_once('../../model/dao/DetailDao.php');
require_once('../../model/dao/CompanionDao.php');
require_once('../../model/dao/UsersDao.php');
require_once('./Header.php');  
use dao\DetailDao;
use dao\CompanionDao;
use dao\UsersDao;
$detailDao = new DetailDao();
$userListClass = '';
if(isset($_GET['id'])) {
    $participant = $detailDao->getParticipant($_GET['id']);
    $companionDao = new CompanionDao();
    $companionList = $companionDao->getCompanionList($participant['id']);
    $userListClass = 'hidden';
} else {
//    header('Location: index.php');
    $participant['id'] = '';
    $participant['name'] = '';
    $participant['email'] = '';
    $participant['occupation'] = 1;
    $participant['occupation_name'] = '社会人';
    $participant['sex'] = 1;
    $participant['sex_name'] = '男性';
    $participant['companion'] = 0;
    $participant['remark'] = '';

    $companionList = array();

    $userDao = new UsersDao();
    $userList = $userDao->getUserList();
}


// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

$title = '参加者情報登録';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/participantInfo.php');
include('../../view/admin/common/footer.php');
?>
