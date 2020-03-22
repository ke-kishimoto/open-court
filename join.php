<?php
// 参加予約の
require_once(dirname(__FILE__).'/model/entity/Participant.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');
require_once(dirname(__FILE__).'/controller/Api.php');
use entity\Participant;
use dao\DetailDao;

$detail = new Participant(
    $_POST['game_id']
    , $_POST['occupation']
    , $_POST['sex']
    , $_POST['name']
    , $_POST['companion']
    , $_POST['remark']
);

$detailDao = new DetailDao();
$detailDao->insert($detail);

$api = new Api();
// 予約の通知
$api->reserve_notify($detail, $_POST['title'], $_POST['date']);

$detail = $detailDao->getDetail($_POST['game_id']);
if ($detail['count'] >= $detail['limit_number']) {
    // 上限に達した通知
    $api->limit_notify($_POST['title'], $_POST['date'], $detail['limit_number'], $detail['count']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登録完了</title>
</head>
<body>
    <p>参加登録完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>
