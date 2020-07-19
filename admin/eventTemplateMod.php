<?php
require_once('../model/dao/EventTemplateDao.php');
use dao\EventTemplateDao;

// テンプレ一覧
$eventTemplateDao = new EventTemplateDao();
$eventTemplateList = $eventTemplateDao->getEventTemplateList();

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
    <title>テンプレート情報修正</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
    <a href="index.php">イベント一覧ページに戻る</a>
    <h2>テンプレート</h2>
    <form action="templateRegister.php" method="post" class="form-group">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            <select name="id" id="template">
            <option value=""></option>
            <?php foreach ($eventTemplateList as $eventTemplate): ?>
                <option value="<?php echo $eventTemplate['id'] ?>"><?php echo $eventTemplate['template_name'] ?></option>
            <?php endforeach ?>
            </select>
            <input type="checkbox" id="new" name="new" value="new">コピーして新規作成
        </p>
        <p>
            テンプレート名<input class="form-control" type="text" id="template_name" name="template_name"  required value="<?php echo $gameInfo['template_name'] ?>">
        </p>
        <p>
            タイトル<input class="form-control" type="text" id="title" name="title" required value="<?php echo $gameInfo['title'] ?>">
        </p>
        <p>
            タイトル略称<input class="form-control" type="text" id="short_title" name="short_title" required value="<?php echo $gameInfo['short_title'] ?>">
        </p>
        <p>
            場所<input class="form-control" type="text" id="place" name="place" required value="<?php echo $gameInfo['place'] ?>">
        </p>
        <p>
            人数上限<input class="form-control" type="number" id="limit_number" name="limit_number" min="1" required value="<?php echo $gameInfo['limit_number'] ?>">
        </p>
        <p>
            詳細<textarea class="form-control" id="detail" name="detail"><?php echo $gameInfo['detail'] ?></textarea>
        </p>
        <p>
            <button class="btn btn-primary" type="submit" name="register">登録</button>
            <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
        </p>
    </form>

    <a href="index.php">イベント一覧ページに戻る</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        'use strict';
        $(function(){ 
            $('#template').change(function() {
                $.ajax({
                url:'../controller/GetEventTemplate.php',
                type:'POST',
                data:{
                    'id':$('#template').val()
                }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    console.log(data);
                    $('#template_name').val(data.template_name);
                    $('#title').val(data.title);
                    $('#short_title').val(data.short_title);
                    $('#place').val(data.place);
                    $('#limit_number').val(data.limit_number);
                    $('#detail').val(data.detail);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {
                })
            })
        });
    
    </script>
</body>
</html>