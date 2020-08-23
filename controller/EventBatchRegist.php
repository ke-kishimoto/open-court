<?php
session_start();
require_once('../model/dao/GameInfoDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
require_once('../model/dao/DetailDao.php');
require_once('./header.php');
use dao\GameInfoDao;
use dao\DefaultCompanionDao;

$gameInfoDao = new GameInfoDao();
date_default_timezone_set('Asia/Tokyo');
$gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'), $_SESSION['user']['email']);

// CSFR対策
// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

if (isset($_SESSION['user'])) {
    $occupation = $_SESSION['user']['occupation'];
    $sex = $_SESSION['user']['sex'];
    $defaultCompanionDao = new DefaultCompanionDao();
    $companions = $defaultCompanionDao->getDefaultCompanionList($_SESSION['user']['id']);

} else {
    $occupation = null;
    $sex = null;
    $companions = [];
}

$title = 'イベント詳細';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/eventBatchRegist.php');
include('../view/common/footer.php');

?>
