<?php

header('Content-type: application/json; charset= UTF-8');
require_once('../../model/dao/InquiryDao.php');
use dao\InquiryDao;

$inquiryDao = new InquiryDao();
// ステータスフラグの更新
$inquiryDao->updateStatusFlg((int)$_POST['id']);
$info = [];
echo json_encode($info);
