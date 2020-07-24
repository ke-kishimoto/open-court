<?php
require_once('../model/entity/GameInfo.php');
require_once('../model/dao/GameInfoDao.php');
use entity\GameInfo;
use dao\GameInfoDao;

session_start();

if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $msg = '';
    if (isset($_POST['register'])) {
        // 登録・修正''
        $msg = '登録';
        $gameInfo = new GameInfo(
            $_POST['title']
            , $_POST['short_title']
            , $_POST['game_date']
            , $_POST['start_time']
            , $_POST['end_time']
            , $_POST['place']
            , $_POST['limit_number']
            , $_POST['detail']
        );
        
        $gameInfoDao = new GameInfoDao();
        if($_POST['id'] == '') {
            $gameInfoDao->insert($gameInfo);
        } else {
            $gameInfo->id = $_POST['id'];
            $gameInfoDao->update($gameInfo);
        }
    } else {
        $gameInfoDao = new GameInfoDao();
        if($_POST['id'] != '') {
            $msg = '削除';
            try {
                $gameInfoDao->getPdo()->beginTransaction();
                $gameInfoDao->delete($_POST['id']);
                $gameInfoDao->getPdo()->commit();
            }catch (Exception $ex) {
                $gameInfoDao->getPdo()->rollBack();
            }
        }
    }
    unset($_SESSION['csrf_token']);
} else {
    header('Location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>イベント登録完了</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
    <?php include('./header.php') ?>
    <p><?php echo $msg ?>イベント登録完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>