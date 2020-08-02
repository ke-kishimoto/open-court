<?php
session_start();
require_once('../../model/dao/DetailDao.php');
require_once('../../model/dao/CompanionDao.php');
use dao\DetailDao;
use dao\CompanionDao;
$detailDao = new DetailDao();
if(isset($_GET['id'])) {
    $participant = $detailDao->getParticipant($_GET['id']);
    $companionDao = new CompanionDao();
    $companionList = $companionDao->getCompanionList($participant['id']);
} else {
//    header('Location: index.php');
    $participant['id'] = '';
    $participant['name'] = '';
    $participant['occupation'] = 1;
    $participant['occupation_name'] = '社会人';
    $participant['sex'] = 1;
    $participant['sex_name'] = '男性';
    $participant['companion'] = 0;
    $participant['remark'] = '';

    $companionList = array();
}


// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

include('./Header.php');  
$title = '参加者情報登録';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
include('../../view/admin/participantInfo.php');
?>
