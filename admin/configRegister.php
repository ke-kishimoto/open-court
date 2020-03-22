<?php
require_once('../model/entity/Config.php');
require_once('../model/dao/ConfigDao.php');
use entity\Config;
use dao\ConfigDao;

session_start();

if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
    // 登録・修正
    $config = new Config(
        $_POST['id']
        , $_POST['line_token']
    );
        
    $configDao = new ConfigDao();
    $configDao->update($config);

    unset($_SESSION['csrf_token']);

    // header('Location: ./');
} else {
    header('Location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>設定変更完了</title>
</head>
<body>
    <p>設定変更完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>