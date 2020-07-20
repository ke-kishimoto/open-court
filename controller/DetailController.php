<?php

require_once(dirname(__FILE__).'/../model/dao/GameInfoDao.php');
use dao\GameInfoDao;

$gameInfoDao = new GameInfoDao();
$date = $_GET['date'];
$result = $gameInfoDao->getGameInfoId($date);

// echo ($result);

if (!empty($result)) {

    header('Location: ../detail.php');
}
