<?php 
require_once(dirname(__FILE__).'/model/entity/Participant.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');
require_once(dirname(__FILE__).'/model/dao/ConfigDao.php');
require_once(dirname(__FILE__).'/controller/Api.php');
use dao\DetailDao;
use dao\GameInfoDao;
use entity\Participant;

$detailDao = new DetailDao();
// LINE通知用に参加者情報とイベント情報を取得
$participant = new Participant($_POST['game_id'], 0, 0, '', $_POST['email'], 0, '');
$id = $detailDao->getParticipantId($participant);


if ($id == null)  {
    $msg = '入力されたメールアドレスによる登録がなかったためキャンセルできませんでした。
            恐れ入りますがやり直りをお願いします。';
} else {
    $participant = $detailDao->getParticipant($id);
    $gameInfoDao = new GameInfoDao();
    $gameInfo = $gameInfoDao->getGameInfo($_POST['game_id']);

    $rowCount = $detailDao->deleteByMailAddress($_POST['game_id'], $_POST['email']);

    $api = new Api();
    $api->cancel_notify($participant, $gameInfo['title'], $gameInfo['game_date']);
    $msg = '予約のキャンセルが完了しました。';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>キャンセル完了</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>
    <p><?php echo $msg ?></p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>

