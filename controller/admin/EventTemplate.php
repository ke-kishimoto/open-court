<?php
session_start();
require_once('../../model/dao/EventTemplateDao.php');
require_once('./Header.php');
use dao\EventTemplateDao;

// テンプレ一覧
$eventTemplateDao = new EventTemplateDao();
$eventTemplateList = $eventTemplateDao->getEventTemplateList();


// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

$title = 'テンプレート登録';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/eventTemplate.php');
include('../../view/admin/common/footer.php');
?>