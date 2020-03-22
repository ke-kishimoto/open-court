<?php
// 日付クリック時のAjax用の処理
header('Content-type: text/plain; charset= UTF-8');

require_once('../model/dao/GameInfoDao.php');
use dao\GameInfoDao;

$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoListByDate($_POST['date']);

foreach ($gameInfoList as $gameInfo) {
    // echo 'aaaa';
    // echo $_POST['date'];

    echo '<hr>';
    echo '<li>';
    if ($_POST['type'] === 'admin') {
        echo '<a href="gameinfomod.php?id=' . $gameInfo['id'] . '">';
    } else {
        echo '<a href="detail.php?id=' . $gameInfo['id'] . '">';
    }
    echo $gameInfo['title'] . '<br>';
    echo '日時：' . $gameInfo['game_date'] . $gameInfo['start_time'] . '～' . $gameInfo['end_time'] . '<br>';
    echo '場所：' . $gameInfo['place'];
    echo '</a>';
    echo '</li>';
}

?>