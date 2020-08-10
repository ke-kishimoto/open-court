<?php
// 日付クリック時のAjax用の処理
header('Content-type: text/plain; charset= UTF-8');

require_once('../../model/dao/GameInfoDao.php');
use dao\GameInfoDao;

$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoListByDate($_POST['date']);
$week = [
    '日', //0
    '月', //1
    '火', //2
    '水', //3
    '木', //4
    '金', //5
    '土', //6
  ];
foreach ($gameInfoList as $gameInfo) {
    // echo 'aaaa';
    // echo $_POST['date'];

    echo '<hr>';
    echo '<li>';
    echo '<a href="EventInfo.php?id=' . $gameInfo['id'] . '">';
    if ($gameInfo['game_date'] < date('Y-m-d')) {
        echo '<span class="event-end">※このイベントは終了しました<br></span>';
    }
    echo $gameInfo['title'] . '<br>';
    // echo '日時：' . $gameInfo['game_date'] . $gameInfo['start_time'] . '～' . $gameInfo['end_time'] . '<br>';
    echo '日時：' . date('n月d日（', strtotime($gameInfo['game_date'])) . $week[date('w', strtotime($gameInfo['game_date']))] . '）';
    echo $gameInfo['start_time'] . '～' . $gameInfo['end_time'] . '<br>';
    echo '場所：' . $gameInfo['place'] . '<br>';
    echo '参加状況：【参加予定：現在' . htmlspecialchars($gameInfo['participants_number']) . '名】定員：' . htmlspecialchars($gameInfo['limit_number']) . '人<br>';
    echo '空き状況：' . htmlspecialchars($gameInfo['mark']);
    echo '</a>';
    echo '</li>';
}

?>