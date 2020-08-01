<?php

require_once(dirname(__FILE__).'/model/dao/GameInfoDao.php');
require_once(dirname(__FILE__).'/model/dao/DefaultCompanionDao.php');
require_once('./model/dao/DetailDao.php');
use dao\GameInfoDao;
use dao\DefaultCompanionDao;
use dao\DetailDao;

$gameInfo = null;
$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';
$pastEvent = false;
$gameInfoDao = new GameInfoDao();
// 試合情報取得
if (isset($_GET['id'])) {
    $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
    $detailDao = new DetailDao();
    $limitFlg = $detailDao->limitCheck($gameInfo['id'], 0);
    $detail = $detailDao->getDetail($gameInfo['id']);
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
    if($limitFlg) {
        $btnClass = 'btn btn-warning';
        $btnLiteral = 'キャンセル待ちとして登録';
    }
    // イベント日が過去の場合は登録フォームを隠す
    date_default_timezone_set('Asia/Tokyo');
    if ($gameInfo['game_date'] < date('Y-m-d')) {
        $pastEvent = true;
    }
}

if (empty($gameInfo)) {
    header('Location: index.php');
}

if(empty($detail)) {
    $detail = array('cnt' => 0
        , 'limit_number' => 0
        , 'sya_women' => 0
        , 'sya_men' => 0
        , 'dai_women' => 0
        , 'dai_men' => 0
        , 'kou_women' => 0
        , 'kou_men' => 0
        , 'waiting_cnt' => 0
    );
}

// CSFR対策
// session_start();

// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

if (isset($_SESSION['user'])) {
    $occupation = $_SESSION['user']['occupation'];
    $sex = $_SESSION['user']['sex'];
    $defaultCompanionDao = new DefaultCompanionDao();
    $companions = $defaultCompanionDao->getDefaultCompanionList($_SESSION['user']['id']);

} else {
    $occupation = null;
    $sex = null;
    $companions = [];
}

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
<?php if($pastEvent) {
    echo ('<p style="color: red;">※終了したイベントのため応募できません</p>');
}
?>
<p><?php echo htmlspecialchars($gameInfo['title']) ?></p>
<p>日付：<?php echo htmlspecialchars($gameInfo['game_date']) ?></p>
<p>時間：<?php echo htmlspecialchars($gameInfo['start_time']) ?>～<?php echo htmlspecialchars($gameInfo['end_time']) ?></p>
<p>場所：<?php echo htmlspecialchars($gameInfo['place']) ?></p>
<p>詳細：<?php echo htmlspecialchars($gameInfo['detail']) ?></p>

<hr>

<div>
    <details>
    <summary>現在の状況</summary>
    <br>
    <p>【参加予定  <span id="cnt"><?php echo htmlspecialchars($detail['cnt']) ?></span>人】【上限  <?php echo htmlspecialchars($gameInfo['limit_number']) ?>人】</p>
    <p>社会人：
        女性 <span id="sya_women"><?php echo htmlspecialchars($detail['sya_women']) ?></span>人、
        男性 <span id="sya_men"><?php echo htmlspecialchars($detail['sya_men']) ?></span>人
    <p>大学・専門：
        女性 <span id="dai_women"><?php echo htmlspecialchars($detail['dai_women']) ?></span>人、
        男性 <span id="dai_men"><?php echo htmlspecialchars($detail['dai_men']) ?></span>人
    </p>
    <p>高校生：
        女性 <span id="kou_women"><?php echo htmlspecialchars($detail['kou_women']) ?></span>人、
        男性 <span id="kou_men"><?php echo htmlspecialchars($detail['kou_men']) ?></span>人
    </p>
    <p>キャンセル待ち：<span id="waiting_cnt"><?php echo htmlspecialchars($detail['waiting_cnt']) ?></span>人</p>
    </details>
</div>

<hr>

<details>
    <summary>参加者リスト</summary>
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

<hr>

<div class="<?php echo $pastEvent === true ? 'hidden' : '' ?>">
    <form id="join_form" action="join.php" method="post" class="form-group">
        <p>【応募フォーム】</p>
        <input type="hidden" id="game_id" name="game_id" value="<?php echo htmlspecialchars($gameInfo['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            職種
            <select id="occupation" name="occupation" class="custom-select mr-sm-2">
                <option value="1" <?php echo $occupation == '1' ? 'selected' : '' ?>>社会人</option>
                <option value="2" <?php echo $occupation == '2' ? 'selected' : '' ?>>大学・専門学校</option>
                <option value="3" <?php echo $occupation == '3' ? 'selected' : '' ?>>高校</option>
            </select>
        </p>
        <p>
            性別
            <select id="sex" name="sex" class="custom-select mr-sm-2">
                <option value="1" <?php echo $sex == '1' ? 'selected' : '' ?>>男性</option>
                <option value="2" <?php echo $sex == '2' ? 'selected' : '' ?>>女性</option>
            </select>
        </p>
        <p>
            名前
            <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['name'] ?>">
        </p>
        <p>
            メール ※新規の方は必須
            <input class="form-control" type="email" name="email" maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['email'] ?>">
        </p>
        <p>
            備考
            <textarea class="form-control" name="remark" maxlength="200"><?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['remark'] ?></textarea>
        </p>
        <p id="douhan-0">
            <!-- 同伴者
            <input class="form-control" type="number" name="companion" required min="0"> 
            -->
            <input id="companion" name="companion" type="hidden" value="<?php echo count($companions); ?>">
            <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
            <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
        </p>
        <?php for($i = 0;$i < count($companions); $i++): ?>
            <div id="douhan-<?php echo $i + 1 ?>">
            <select id="occupation-<?php echo $i ?>" name="occupation-<?php echo $i ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companions[$i]['occupation'] == '1' ? 'selected' : ''; ?>>社会人</option>
                <option value="2" <?php echo $companions[$i]['occupation'] == '2' ? 'selected' : ''; ?>>大学・専門学校</option>
                <option value="3" <?php echo $companions[$i]['occupation'] == '3' ? 'selected' : ''; ?>>高校</option>
            </select>
            <select id="sex-<?php echo $i + 1 ?>" name="sex-<?php echo $i + 1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companions[$i]['sex'] == '1' ? 'selected' : ''; ?>>男性</option>
                <option value="2" <?php echo $companions[$i]['sex'] == '2' ? 'selected' : ''; ?>>女性</option>
            </select>
            <input id="name-<?php echo $i + 1 ?>" class="form-control" type="text" name="name-<?php echo $i + 1 ?>" required maxlength="50" value="<?php echo $companions[$i]['name']; ?>">
            </div>
        <?php endfor ?>
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($gameInfo['title']) ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($gameInfo['game_date']) ?>">
            <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
        <a class="btn btn-danger" href="cancelForm.php?gameid=<?php echo htmlspecialchars($gameInfo['id']) ?>" >参加のキャンセル</a>
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
            if(num > 9){
                $('#douhanErrMsg').css('display','block');
                return
            }
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