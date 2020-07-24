<?php

require_once(dirname(__FILE__).'/model/dao/GameInfoDao.php');
require_once('./model/dao/DetailDao.php');
use dao\GameInfoDao;
use dao\DetailDao;

$gameInfo = null;
$limitFlg = false;
$btnClass = "btn btn-primary";
$btnLiteral = "登録";
$gameInfoDao = new GameInfoDao();
// 試合情報取得
if (isset($_GET['id'])) {
    $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
    $detailDao = new DetailDao();
    $limitFlg = $detailDao->limitCheck($gameInfo['id'], 0);
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
    if($limitFlg) {
        $btnClass = "btn btn-warning";
        $btnLiteral = "キャンセル待ちとして登録";
    }
}

if (empty($gameInfo)) {
    header('Location: index.php');
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
<?php include('./header.php') ?>
<p><?php echo htmlspecialchars($gameInfo['title']) ?></p>
<p>日付：<?php echo htmlspecialchars($gameInfo['game_date']) ?></p>
<p>時間：<?php echo htmlspecialchars($gameInfo['start_time']) ?>～<?php echo htmlspecialchars($gameInfo['end_time']) ?></p>
<p>場所：<?php echo htmlspecialchars($gameInfo['place']) ?></p>
<p>詳細：<?php echo htmlspecialchars($gameInfo['detail']) ?></p>

<?php include('./participationInfo.php'); ?>
<br>
<details>
<summary>参加者詳細</summary>
<?php foreach ((array)$participantList as $participant): ?>
    <?php if($participant['main'] === '1'): ?>
        <hr>
    <?php endif ?>
    <p>
        <?php echo htmlspecialchars($participant['waiting_name']); ?>
        <?php echo $participant['waiting_flg'] === '1' ? '<br>' : ''; ?>
        <?php echo htmlspecialchars($participant['companion_name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['occupation_name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['sex_name']); ?>&nbsp;&nbsp;
    </p>
<?php endforeach; ?>
</details>

<br>
<hr>
<div>
<form id="join_form" action="join.php" method="post" class="form-group">
    <p>【応募フォーム】</p>
    <input type="hidden" id="game_id" name="game_id" value="<?php echo $gameInfo['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
    職種
    <select id="occupation" name="occupation" class="custom-select mr-sm-2">
        <option value="1">社会人</option>
        <option value="2">大学・専門学校</option>
        <option value="3">高校</option>
      </select>
    </p>
    
    <p>
    性別
    <select id="sex" name="sex" class="custom-select mr-sm-2">
        <option value="1">男性</option>
        <option value="2">女性</option>
    </select>
    </p>
    <p>
        名前
        <input id="name" class="form-control" type="text" name="name" required>
    </p>
    <p>
        メール
        <input class="form-control" type="email" name="email">
    </p>
    <p>
        備考
        <textarea class="form-control" name="remark"></textarea>
    </p>
    <p id="douhan-0">
        <!-- 同伴者
        <input class="form-control" type="number" name="companion" required min="0"> -->
        <input id="companion" name="companion" type="hidden" value="0">
        <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
        <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
    </p>
    <input type="hidden" name="title" value="<?php echo $gameInfo['title'] ?>">
    <input type="hidden" name="date" value="<?php echo $gameInfo['game_date'] ?>">
    <button class="<?php echo $btnClass ?>" type="submit"><?php echo $btnLiteral ?></button>
    <a class="btn btn-danger" href="cancelForm.php?gameid=<?php echo $gameInfo['id'] ?>" >参加のキャンセル</a>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    var gameId = document.getElementById("game_id").value;
    if(gameId === null || gameId === '') {
        document.getElementById("join_form").classList.add('hidden');
    }
</script>
<script>
    $(function() {
        $('#btn-add').on('click', function() {
            var num = Number($('#companion').val());
            var current = $('#douhan-' + num);
            num++;
            var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
            div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num));
            div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num));
            div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(''));
            div.append($('<br>'));
            current.after(div);
            $('#companion').val(num);
        });
        $('#btn-del').on('click', function() {
            var num = Number($('#companion').val());
            if(num > 0) {
                $('#douhan-' + num).remove();
                num--;
            }
            $('#companion').val(num);
        });
    })
</script>
</body>
</html>