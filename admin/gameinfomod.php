<?php

require_once('../model/dao/GameInfoDao.php');
require_once('../model/dao/DetailDao.php');

$gameInfo = null;
$gameInfoDao = new GameInfoDao();
// 試合情報取得
if (isset($_GET['id'])) {
    $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
}
if (empty($gameInfo)) {
    $gameInfo['id'] = '';
    $gameInfo['title'] = '';
    $gameInfo['game_date'] = '';
    $gameInfo['start_time'] ='';
    $gameInfo['end_time'] ='';
    $gameInfo['place'] ='';
    $gameInfo['detail'] ='';
}

// 参加者情報取得
$detail = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $detail = $detailDao->getDetail($gameInfo['id']);
}

if(empty($detail)) {
    $detail = array('count' => 0
        , 'sya_women' => 0
        , 'sya_men' => 0
        , 'dai_women' => 0
        , 'dai_men' => 0
        , 'kou_women' => 0
        , 'kou_men' => 0);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント情報修正</title>
</head>
<body>
    <a href="index.php">イベント一覧ページに戻る</a>
    <h2>イベント情報追加・修正</h2>
    <form action="register.php" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $gameInfo['id'] ?>">
        <p>
            タイトル：<input type="text" name="title" require value="<?php echo $gameInfo['title'] ?>">
        </p>
        <p>
            日程：<input type="date" name="game_date" require value="<?php echo $gameInfo['game_date'] ?>">
        </p>
        <p>
            開始時間：<input type="time" name="start_time" require value="<?php echo $gameInfo['start_time'] ?>">
        </p>
        <p>
            終了時間：<input type="time" name="end_time" require value="<?php echo $gameInfo['end_time'] ?>">
        </p>
        <p>
            場所：<input type="text" name="place" require value="<?php echo $gameInfo['place'] ?>">
        </p>
        <p>
            詳細：<textarea name="detail"></textarea>
        </p>
        <p>
            <button type="submit">登録</button>
        </p>
    </form>

    <p>【参加予定  <?php echo $detail['count'] ?>人】</p>
    <p>社会人：女性 <?php echo $detail['sya_women'] ?>人、男性 <?php echo $detail['sya_men'] ?>人
    <p>大学・専門：女性 <?php echo $detail['dai_women'] ?>人、男性 <?php echo $detail['dai_men'] ?>人</p>
    <p>高校生：女性 <?php echo $detail['kou_women'] ?>人、男性 <?php echo $detail['kou_men'] ?>人</p>
</body>
</html>