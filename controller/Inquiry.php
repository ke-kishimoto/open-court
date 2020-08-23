<?php
session_start();
require_once('./header.php');  
require_once('../model/dao/GameInfoDao.php');
use dao\GameInfoDao;

$gameInfoDao = new GameInfoDao();
$gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'));

// CSFR対策
// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

$title = 'お問い合わせ';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/inquiry.php');
include('../view/common/footer.php');

?>