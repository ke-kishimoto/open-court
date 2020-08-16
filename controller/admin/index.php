<?php
require_once('../../model/dao/GameInfoDao.php');
use dao\GameInfoDao;
$gameInfoDao = new GameInfoDao();
require_once("../calendar.php"); 
require_once('./Header.php');  
$gameInfoList = $gameInfoDao->getGameInfoList($year, $month);
$week = [
    '日', //0
    '月', //1
    '火', //2
    '水', //3
    '木', //4
    '金', //5
    '土', //6
  ];
$title = 'イベントカレンダー';
$adminFlg = '1';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/calendar.php');
include('../../view/eventList.php');
include('../../view/admin/common/footer.php');
?>