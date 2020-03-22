<?php
require_once('../model/entity/GameInfo.php');
require_once('../model/dao/GameInfoDao.php');
use entity\GameInfo;
use dao\GameInfoDao;
$msg = '';
if (isset($_POST['register'])) {
    // 登録・修正''
    $msg = '登録';
    $gameInfo = new GameInfo(
        $_POST['title']
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
        $gameInfoDao->delete($_POST['id']);
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>完了</title>
</head>
<body>
    <p><?php echo $msg ?>完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>