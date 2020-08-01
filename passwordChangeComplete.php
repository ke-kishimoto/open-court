<?php
require_once(dirname(__FILE__).'/model/entity/Users.php');
require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
use entity\Users;
use dao\UsersDao;


if (!empty($_POST)) {
    $errMsg = '';
    $usersDao = new UsersDao();

    //パスワードチェック
    if (($_POST['password']) != ($_POST['rePassword'])) {
        $errMsg = 'パスワード(再入力)が同じでありません';
    }

    if(empty($errMsg)){
        $usersDao->updatePass($_SESSION['user']['id'], password_hash($_POST['password'], PASSWORD_DEFAULT));
    }
}

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>パスワード変更</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>
<div>
    <p>パスワードを変更しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    
</script>
</body>
</html>