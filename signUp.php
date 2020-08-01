<?php
// session_start();
// 新規登録・アカウント情報修正
require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
require_once(dirname(__FILE__).'/model/dao/DefaultCompanionDao.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;

$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';

if(!empty($_GET) && !empty($_SESSION['user'])) {
    $usersDao = new UsersDao();
    $defultCompanionDao = new DefaultCompanionDao();
    $user = $usersDao->getUserById($_GET['id']);
    $companions = $defultCompanionDao->getDefaultCompanionList($user['id']);
    $title = 'アカウント情報修正';
    $mode = 'update';
    $id = $_GET['id'];
    $passChange = '';
} else {
    $user = array(
        'id' => ''
        , 'name' => ''
        , 'occupation' => '1'
        , 'sex' => '1'
        , 'email' => ''
        , 'password' => ''
        , 'remark' =>''
    );
    $companions = [];
    $title = '新規登録';
    $mode = 'new';
    $id = '';
    $passChange = 'hidden';
}

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>

<div>
  <div class="explain-box">
      <span class="explain-tit"><?php echo $title ?></span>
      <p>イベントへ応募時、以下の入力項目がデフォルトで設定されます</p>
  </div>
  <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($passChange) ?>" href="passwordChange.php" role="button">パスワード変更</a>
    <form id="signUp_form" action="signUpComplete.php" method="post" class="form-group">
        <input type="hidden" id="mode" name="mode" value="<?php echo $mode ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            職種
            <select id="occupation" name="occupation" class="custom-select mr-sm-2">
                <option value="1" <?php echo $user['occupation'] == '1' ? 'selected' : '' ?>>社会人</option>
                <option value="2" <?php echo $user['occupation'] == '2' ? 'selected' : '' ?>>大学・専門学校</option>
                <option value="3" <?php echo $user['occupation'] == '3' ? 'selected' : '' ?>>高校</option>
            </select>
        </p>
        <p>
            性別
            <select id="sex" name="sex" class="custom-select mr-sm-2">
                <option value="1" <?php echo $user['sex'] == '1' ? 'selected' : '' ?>>男性</option>
                <option value="2" <?php echo $user['sex'] == '2' ? 'selected' : '' ?>>女性</option>
            </select>
        </p>
        <p>
            名前
            <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo $user['name'] ?>">
        </p>
        <p>
            メール
            <input class="form-control" type="email" name="email" required maxlength="50" value="<?php echo $user['email'] ?>">
        </p>
        <div id="password-area">
            <p>
                パスワード
                <input class="form-control" type="password" name="password" required maxlength="50">
            </p>
            <p>
                パスワード(再入力)
                <input class="form-control" type="password" name="rePassword" required maxlength="50">
            </p>
        </div>
        <p>
            備考
            <textarea class="form-control" name="remark" maxlength="200"><?php echo $user['remark'] ?></textarea>
        </p>
        <p id="douhan-0">
            <input id="companion" name="companion" type="hidden" value="<?php echo count($companions); ?>">
            <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
            <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
        </p>
        <?php for($i = 0;$i < count($companions); $i++): ?>
            <div id="douhan-<?php echo $i + 1 ?>">
            <select id="occupation-<?php echo $i + 1 ?>" name="occupation-<?php echo $i + 1 ?>" class="custom-select mr-sm-2">
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
        <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
    </form>
    <br>
    <form action="withdrawal.php">
        <button class="btn btn-danger" type="submit">退会</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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
        if($('#mode').val() === 'update') {
            $('#password-area').remove();
        }
    })
</script>
</body>
</html>