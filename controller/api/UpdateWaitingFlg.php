<?php

header('Content-type: application/json; charset= UTF-8');
require_once('../../model/dao/DetailDao.php');
use dao\DetailDao;

$detailDao = new DetailDao();
// キャンセル待ちフラグの更新
$participant = $detailDao->updateWaitingFlg($_POST['id']);

$info = $detailDao->getDetail($_POST['game_id']);
$info['waiting_flg'] = $participant['waiting_flg'];

echo json_encode($info);
