<?php

require_once(dirname(__FILE__).'/model/dao/GameInfoDao.php');
require_once('./model/dao/DetailDao.php');
use dao\GameInfoDao;
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
    $gameInfo['limit_number'] = 0;
    $gameInfo['detail'] ='';
}

// CSFR対策
session_start();

// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>試合詳細</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<a href="index.php">イベント一覧に戻る</a>
<p><?php echo htmlspecialchars($gameInfo['title']) ?></p>
<p>日付：<?php echo htmlspecialchars($gameInfo['game_date']) ?></p>
<p>時間：<?php echo htmlspecialchars($gameInfo['start_time']) ?>～<?php echo htmlspecialchars($gameInfo['end_time']) ?></p>
<p>場所：<?php echo htmlspecialchars($gameInfo['place']) ?></p>
<p>詳細：<?php echo htmlspecialchars($gameInfo['detail']) ?></p>

<?php include('./participationInfo.php'); ?>

<br>
<hr>
<div>
<form id="join_form" action="join.php" method="post" class="form-group">
    <p>【応募フォーム】</p>
    <input type="hidden" id="game_id" name="game_id" value="<?php echo $gameInfo['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <!-- <p>
        職種：
        社会人<input type="radio" name="occupation" value="1" required>
        &nbsp;&nbsp;
        大学・専門<input type="radio" name="occupation" value="2">
        &nbsp;&nbsp;
        高校生<input type="radio" name="occupation" value="3">
    </p> -->
    <p>
    職種
    <select name="occupation" class="custom-select mr-sm-2">
        <option value="1">社会人</option>
        <option value="2">大学・専門学校</option>
        <option value="3">高校</option>
      </select>
    </p>
    
    <!-- <p>
        性別：
        男性<input type="radio" name="sex" value="1" required>
        &nbsp;&nbsp;
        女性<input type="radio" name="sex" value="2">
    </p> -->
    <p>
    性別
    <select name="sex" class="custom-select mr-sm-2">
        <option value="1">男性</option>
        <option value="2">女性</option>
    </select>
    </p>
    <p>
        名前
        <input class="form-control" type="text" name="name" required>
    </p>
    <p>
        同伴者
        <input class="form-control" type="number" name="companion" required min="0">
    </p>
    <p>
        備考
        <textarea class="form-control" name="remark"></textarea>
    </p>
    <input type="hidden" name="title" value="<?php echo $gameInfo['title'] ?>">
    <input type="hidden" name="date" value="<?php echo $gameInfo['game_date'] ?>">
    <button class="btn btn-primary" type="submit">参加</button>
</form>
</div>
<script>
    var gameId = document.getElementById("game_id").value;
    if(gameId === null || gameId === '') {
        document.getElementById("join_form").classList.add('hidden');
    }
</script>
</body>
</html>