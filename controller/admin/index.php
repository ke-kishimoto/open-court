<?php
require_once('../../model/dao/GameInfoDao.php');
use dao\GameInfoDao;
$gameInfoDao = new GameInfoDao();
require_once("../calendar.php"); 
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
include('./Header.php');  
$title = 'イベントカレンダー';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
include('../../view/calendar.php');
include('../../view/admin/eventCalendar.php');
?>