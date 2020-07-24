<?php 
require_once(dirname(__FILE__).'/model/entity/Participant.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');
require_once(dirname(__FILE__).'/model/dao/ConfigDao.php');
require_once(dirname(__FILE__).'/controller/Api.php');
use dao\DetailDao;

$detailDao = new DetailDao();
$rowCount = $detailDao->deleteByMailAddress($_POST['game_id'], $_POST['email']);

if ($rowCount === 0) {
    $msg = '入力されたメールアドレスによる登録がなかったためキャンセルできませんでした。
            恐れ入りますがやり直りをお願いします。';
} else {
    $msg = '予約のキャンセルが完了しました。';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>キャンセル完了</title>
</head>
<body>
    <p><a href="index.php">イベント一覧に戻る</a></p>
    <p><?php echo $msg ?></p>
</body>
</html>

