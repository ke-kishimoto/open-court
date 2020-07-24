<?php
// 参加予約の
require_once(dirname(__FILE__).'/model/entity/Participant.php');
require_once(dirname(__FILE__).'/model/entity/Companion.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');
require_once(dirname(__FILE__).'/model/dao/ConfigDao.php');
require_once(dirname(__FILE__).'/model/dao/CompanionDao.php');
require_once(dirname(__FILE__).'/controller/Api.php');
use entity\Participant;
use entity\Companion;
use dao\DetailDao;
use dao\CompanionDao;

session_start();

if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $detailDao = new DetailDao();
    if($detailDao->limitCheck($_POST['game_id'], 1 + $_POST['companion'])) {
        $waitingFlg = 1;
    } else {
        $waitingFlg = 0;
    }

    $detail = new Participant(
        $_POST['game_id']
        , $_POST['occupation']
        , $_POST['sex']
        , $_POST['name']
        , $_POST['email']
        , $waitingFlg 
        , $_POST['remark']
    );
    
    $detailDao->insert($detail);

    // 同伴者の登録
    if($_POST['companion'] > 0) {
        $id = $detailDao->getParticipantId($detail);
        $companionDao = new CompanionDao();
        for($i = 1; $i <= $_POST['companion']; $i++) {
            $companion = new Companion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
            $companionDao->insert($companion);
        }
    }
    
    // 予約の通知
    $api = new Api();
    $api->reserve_notify($detail, $_POST['title'], $_POST['date']);

    // if ($waitingFlg) {
    //     // 上限に達した通知
    //     $api->limit_notify($_POST['title'], $_POST['date'], $detail['limit_number'], $detail['count']);
    // }
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
    <title>登録完了</title>
</head>
<body>
    <p>参加登録完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>
