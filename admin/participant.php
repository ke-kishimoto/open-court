<?php
require_once('../model/dao/DetailDao.php');
use dao\DetailDao;
$detailDao = new DetailDao();
$participant = $detailDao->getParticipant($_GET['id']);

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
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
    職種
    <select name="occupation" class="custom-select mr-sm-2">
        <option value="<?php echo $participant['occupation'] ?>"><?php echo $participant['occupation_name'] ?></option>
        <option value="1">社会人</option>
        <option value="2">大学・専門学校</option>
        <option value="3">高校</option>
      </select>
    </p>
    <p>
    性別
    <select name="sex" class="custom-select mr-sm-2">
        <option value="<?php echo $participant['sex'] ?>"><?php echo $participant['sex_name'] ?></option>
        <option value="1">男性</option>
        <option value="2">女性</option>
    </select>
    </p>
    <p>
        名前
        <input class="form-control" type="text" name="name" value="<?php echo $participant['name'] ?>" required>
    </p>
    <p>
        同伴者
        <input class="form-control" type="number" name="companion" value="<?php echo $participant['companion'] ?>" required min="0">
    </p>
    <p>
        備考
        <textarea class="form-control" name="remark"><?php echo $participant['remark'] ?></textarea>
    </p>
    <p>
        <button class="btn btn-primary" type="submit" name="register">登録</button>
        <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
    </p>
</form>
<!-- <a href="#">戻る</a> -->
</body>
</html>