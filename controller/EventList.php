<?php
header('Content-type: text/plain; charset= UTF-8');

require_once('../model/dao/GameInfoDao.php');

$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoListByDate($_POST['date']);

foreach ($gameInfoList as $gameInfo) {
    echo 'aaaa';
}

?>