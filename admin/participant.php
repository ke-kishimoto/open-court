<?php
require_once('../model/dao/DetailDao.php');
require_once('../model/dao/CompanionDao.php');
use dao\DetailDao;
use dao\CompanionDao;
$detailDao = new DetailDao();
if(isset($_GET['id'])) {
    $participant = $detailDao->getParticipant($_GET['id']);
    $companionDao = new CompanionDao();
    $companionList = $companionDao->getCompanionList($participant['id']);
} else {
//    header('Location: index.php');
    $participant['id'] = '';
    $participant['name'] = '';
    $participant['occupation'] = 1;
    $participant['occupation_name'] = '社会人';
    $participant['sex'] = 1;
    $participant['sex_name'] = '男性';
    $participant['companion'] = 0;
    $participant['remark'] = '';

    $companionList = array();
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
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>参加者情報情報修正</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
<h3>参加者情報修正</h3>
<form action="participantRegister.php" method="post" class="form-group">
    <input type="hidden" id="id" name="id" value="<?php echo $participant['id'] ?>">
    <input type="hidden" name="game_id" value="<?php echo $_GET['game_id'] ?>">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
    職種
    <select id="occupation" name="occupation" class="custom-select mr-sm-2">
        <option value="1" <?php echo $participant['occupation'] === '1' ? 'selected' : '' ?> >社会人</option>
        <option value="2" <?php echo $participant['occupation'] === '2' ? 'selected' : '' ?> >大学・専門学校</option>
        <option value="3" <?php echo $participant['occupation'] === '3' ? 'selected' : '' ?> >高校</option>
      </select>
    </p>
    <p>
    性別
    <select id="sex" name="sex" class="custom-select mr-sm-2">
        <option value="1" <?php echo $participant['sex'] === '1' ? 'selected' : '' ?> >男性</option>
        <option value="2" <?php echo $participant['sex'] === '2' ? 'selected' : '' ?> >女性</option>
    </select>
    </p>
    <p>
        名前
        <input class="form-control" type="text" id="name" name="name" value="<?php echo $participant['name'] ?>" required>
    </p>
    <p>
        メール
        <input class="form-control" type="email" name="email">
    </p>
    <p>
        備考
        <textarea class="form-control" name="remark"><?php echo $participant['remark'] ?></textarea>
    <div id="douhan-0">
        </p>
            <input id="companion" name="companion" type="hidden" value="<?php echo count((array)$companionList) ?>">
            <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
        </p>
    </div>
    <?php for($i = 0;$i < count($companionList); $i++): ?>
        <div id="douhan-<?php echo $i+1 ?>"> 
            <select id="occupation-<?php echo $i+1 ?>" name="occupation-<?php echo $i+1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companionList[$i]['occupation'] === '1' ? 'selected' : '' ?> >社会人</option>
                <option value="2" <?php echo $companionList[$i]['occupation'] === '2' ? 'selected' : '' ?> >大学・専門学校</option>
                <option value="3" <?php echo $companionList[$i]['occupation'] === '3' ? 'selected' : '' ?> >高校</option>
            </select>
        
            <select id="sex-<?php echo $i ?>" name="sex-<?php echo $i+1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companionList[$i]['sex'] === '1' ? 'selected' : '' ?> >男性</option>
                <option value="2" <?php echo $companionList[$i]['sex'] === '2' ? 'selected' : '' ?> >女性</option>
            </select>
            <input id="name-<?php echo $i+1 ?>" class="form-control" type="text" name="name-<?php echo $i+1 ?>" required value="<?php echo $companionList[$i]['name'] ?>">
        </div>
    <?php endfor ?>
    <p>
        <button class="btn btn-primary" type="submit" name="register">登録</button>
        <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
    </p>

</form>
<p><a href="./gameinfomod.php?id=<?php echo $_GET['game_id'] ?>">イベント情報ページに戻る</a></p>
<p><a href="index.php">イベント一覧に戻る</a></p>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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