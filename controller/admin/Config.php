<?php
session_start();
require_once('../../model/dao/ConfigDao.php');
use dao\ConfigDao;

$configDao = new ConfigDao();
// いずれユーザーIDにする
$config = $configDao->getConfig(1);


// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

include('./Header.php');  
$title = 'システム設定';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
include('../../view/admin/config.php');
?>
