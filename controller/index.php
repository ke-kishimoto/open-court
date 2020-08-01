<?php

require_once('../model/dao/GameInfoDao.php');
require_once("../calendar.php"); 
use dao\GameInfoDao;
$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoList($year, $month);
$week = [
    '日', //0
    '月', //1
    '火', //2
    '水', //3
    '木', //4
    '金', //5
    '土', //6
  ];
include('./header.php');  
$title = 'イベントカレンダー';
include('../head.php');
include('../header.php');
include('../eventCalendar.php');
?>