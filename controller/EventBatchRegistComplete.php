<?php
session_start();
// 参加予約の登録
require_once('../model/entity/Participant.php');
require_once('../model/entity/Companion.php');
require_once('../model/dao/ConfigDao.php');
require_once('./EventParticipantController.php');
require_once('./header.php');
use controller\EventParticipantController;
use entity\Participant;
use entity\Companion;

$errMsg = '';
if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $controller = new EventParticipantController();

    $participant = new Participant(
        0
        , (int)$_POST['occupation']
        , (int)$_POST['sex']
        , $_POST['name']
        , $_POST['email']
        , 0 
        , $_POST['remark']
    );
    
    $companion = [];
    for($i = 1; $i <= $_POST['companion']; $i++) {
        $companion[$i-1] = new Companion(0, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
    }

    $count = $controller->multipleParticipantRegist($_POST['game_id'], $participant, $companion);
    if($count) {
        $msg = "{$count}件のイベントに登録しました。";
    } else {
        $msg = '登録されたイベントはありませんでした。';
    }

    unset($_SESSION['csrf_token']);
    $title = 'イベント参加登録完了';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/complete.php');
    include('../view/common/footer.php');

} else {
    header('Location: ./index.php');
}
