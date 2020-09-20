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
        (int)$_POST['game_id']
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

    $errMsg = $controller->oneParticipantRegist($participant, $companion);

    unset($_SESSION['csrf_token']);
    if(empty($errMsg)) {
        $title = 'イベント参加登録完了';
        $msg = 'イベント参加登録が完了しました。';
        $msg2 = <<<EOF
        変更、及びキャンセルの場合は必ず本システムからの変更かキャンセルを行うか、
        お問い合わせフォームにより管理者へご連絡ください。
        EOF;
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/complete.php');
        include('../view/common/footer.php');
    } else {
        $title = 'イベント参加登録完了';
        $msg = '入力されたメールアドレスで既に登録済みです。';
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/complete.php');
        include('../view/common/footer.php');
    }
} else {
    header('Location: ./index.php');
}

?>
