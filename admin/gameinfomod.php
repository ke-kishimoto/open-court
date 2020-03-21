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
$participantList = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $detail = $detailDao->getDetail($gameInfo['id']);
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
    <a href="index.php">イベント一覧ページに戻る</a>
    <h2>イベント情報編集</h2>
    <form action="register.php" method="post" class="form-group">
        <input type="hidden" id="id" name="id" value="<?php echo $gameInfo['id'] ?>">
        <p>
            タイトル<input class="form-control" type="text" name="title"  require value="<?php echo $gameInfo['title'] ?>">
        </p>
        <p>
            日程<input class="form-control" type="date" name="game_date" require value="<?php echo $gameInfo['game_date'] ?>">
        </p>
        <p>
            開始時間<input class="form-control" type="time" name="start_time" require value="<?php echo $gameInfo['start_time'] ?>">
        </p>
        <p>
            終了時間<input class="form-control" type="time" name="end_time" require value="<?php echo $gameInfo['end_time'] ?>">
        </p>
        <p>
            場所<input class="form-control" type="text" name="place" require value="<?php echo $gameInfo['place'] ?>">
        </p>
        <p>
            詳細<textarea class="form-control" name="detail"><?php echo $gameInfo['detail'] ?></textarea>
        </p>
        <p>
            <button class="btn btn-primary" type="submit">登録</button>
            <button class="btn btn-primary" type="submit">削除</button>
        </p>
    </form>

    <hr>
    <div>
        <h3>集計情報</h3>
        <p>【参加予定  <?php echo $detail['count'] ?>人】</p>
        <p>社会人：女性 <?php echo $detail['sya_women'] ?>人、男性 <?php echo $detail['sya_men'] ?>人
        <p>大学・専門：女性 <?php echo $detail['dai_women'] ?>人、男性 <?php echo $detail['dai_men'] ?>人</p>
        <p>高校生：女性 <?php echo $detail['kou_women'] ?>人、男性 <?php echo $detail['kou_men'] ?>人</p>
    </div>
    <hr>
    <div>
        <h3>参加者詳細</h3>
        <?php foreach ($participantList as $gameInfo): ?>
            <p>
            <?php echo $gameInfo['name']; ?>&nbsp;&nbsp;
            <?php echo $gameInfo['occupation']; ?>&nbsp;&nbsp;
            <?php echo $gameInfo['sex']; ?>
            </p>
            <p>
            <?php echo $gameInfo['remark']; ?>
            </p>
            <hr>
        <?php endforeach; ?>
    </div>
    <a href="index.php">イベント一覧ページに戻る</a>

</body>
</html>