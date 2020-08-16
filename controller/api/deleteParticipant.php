<?php

header('Content-type: application/json; charset= UTF-8');
require_once('../../model/dao/DetailDao.php');
use dao\DetailDao;

$detailDao = new DetailDao();
// 削除
$participant = $detailDao->delete($_POST['participant_id']);

$info = $detailDao->getDetail($_POST['game_id']);

echo json_encode($info);
