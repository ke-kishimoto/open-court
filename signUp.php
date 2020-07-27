<?php
require_once(dirname(__FILE__).'/model/dao/SignUpDao.php');
use dao\SignUpDao;
$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';

session_start();
if (!empty($_POST)) {

  $signUpDao = new SignUpDao();

  //パスワードチェック
  if (($_POST['password']) != ($_POST['rePassword'])) {
      $errMsg = 'パスワード(再入力)が同じでありません';
  // メールアドレスによる重複チェック
  }else if(!$signUpDao->existsCheck($_POST['email'])){
      $errMsg = '既に登録済みです';
  }
} 
session_destroy();

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>新規登録</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>

<div>
  <div class="explain-box">
      <span class="explain-tit">新規登録</span>
      <p>イベントへ応募時、以下の入力項目がデフォルトで設定されます</p>
  </div>
    <form id="join_form" action="signUp.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
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
            <input id="name" class="form-control" type="text" name="name" required maxlength="50">
        </p>
        <p>
            メール
            <input class="form-control" type="email" name="email" required maxlength="50">
        </p>
        <p>
            パスワード
            <input class="form-control" type="password" name="password" required maxlength="50">
        </p>
        <p>
            パスワード(再入力)
            <input class="form-control" type="password" name="rePassword" required maxlength="50">
        </p>
        <p>
            備考
            <textarea class="form-control" name="remark" maxlength="200"></textarea>
        </p>
        <p id="douhan-0">
            <input id="companion" name="companion" type="hidden" value="0">
            <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
            <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
        </p>
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($gameInfo['title']) ?>">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($gameInfo['game_date']) ?>">
        <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
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