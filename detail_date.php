<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>試合詳細</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="index.php">イベント一覧に戻る</a>
<hr>
<?php

require_once(dirname(__FILE__).'/model/dao/GameInfoDao.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');

$gameInfoDao = new GameInfoDao();
// 試合情報取得
if (isset($_GET['date'])) {
    $gameInfoList = $gameInfoDao->getGameInfoListByDate($_GET['date']);
}
?>
<?php foreach ($gameInfoList as $gameInfo): ?>
<?php
// 参加者情報取得
$detailDao = new DetailDao();
$detail = $detailDao->getDetail($gameInfo['id']);

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

<p><?php echo $gameInfo['title'] ?></p>
<p>日付：<?php echo $gameInfo['game_date'] ?></p>
<p>時間：<?php echo $gameInfo['start_time'] ?>～<?php echo $gameInfo['end_time'] ?></p>
<p>場所：<?php echo $gameInfo['place'] ?></p>
<p>詳細：<?php echo $gameInfo['detail'] ?></p>

<p>【参加予定  <?php echo $detail['count'] ?>人】</p>
<p>社会人：女性 <?php echo $detail['sya_women'] ?>人、男性 <?php echo $detail['sya_men'] ?>人
<p>大学・専門：女性 <?php echo $detail['dai_women'] ?>人、男性 <?php echo $detail['dai_men'] ?>人</p>
<p>高校生：女性 <?php echo $detail['kou_women'] ?>人、男性 <?php echo $detail['kou_men'] ?>人</p>

<br>
<form id="join_form" action="join.php" method="post">
    <p>【応募フォーム】</p>
    <input type="hidden" id="game_id" name="game_id" value="<?php echo $gameInfo['id'] ?>">
    <p>
        職種：
        社会人<input type="radio" name="occupation" value="1" required>
        &nbsp;&nbsp;
        大学・専門<input type="radio" name="occupation" value="2">
        &nbsp;&nbsp;
        高校生<input type="radio" name="occupation" value="3">
    </p>
    <p>
        性別：
        男性<input type="radio" name="sex" value="1" required>
        &nbsp;&nbsp;
        女性<input type="radio" name="sex" value="2">
    </p>
    <p>
        名前：
        <input type="text" name="name" required>
    </p>
    <button type="submit">参加</button>
</form>

<hr>

<?php endforeach; ?>
</body>
</html>