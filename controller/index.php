<?php
session_start();
require_once('./model/dao/GameInfoDao.php');
require_once("./controller/calendar.php"); 
require_once('./controller/header.php');  
use dao\GameInfoDao;
$gameInfoDao = new GameInfoDao();
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
$adminFlg = '0';
include('./view/common/head.php');
include('./view/common/header.php');
include('./view/calendar.php');
include('./view/eventList.php');
include('./view/common/footer.php');
?>