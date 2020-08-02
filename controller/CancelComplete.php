<?php
session_start();
require_once('../model/entity/Participant.php');
require_once('../model/dao/DetailDao.php');
require_once('../model/dao/ConfigDao.php');
require_once('../model/dao/UsersDao.php');
require_once('../controller/api/LineApi.php');
require_once('./header.php');
use dao\DetailDao;
use dao\UsersDao;
use dao\GameInfoDao;
use entity\Participant;

$errMsg = '';
if(isset($_POST)) {
    $detailDao = new DetailDao();
    // LINE通知用に参加者情報とイベント情報を取得
    $participant = new Participant($_POST['game_id'], 0, 0, '', $_POST['email'], 0, '');
    $id = $detailDao->getParticipantId($participant);
    $msg = '';
    if ($id == null)  {
        $errMsg = '入力されたメールアドレスによる登録がありませんでした。';
    } else {
        if(isset($_POST['password']) && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $usersDao = new UsersDao();
            $user = $usersDao->getUserById($userId);
            if(!password_verify($_POST['password'], $user['password'])) {
                $errMsg = 'パスワードが異なります';
            }
        }
        if(empty($errMsg)) {
            $participant = $detailDao->getParticipant($id);
            $gameInfoDao = new GameInfoDao();
            $gameInfo = $gameInfoDao->getGameInfo($_POST['game_id']);
        
            $rowCount = $detailDao->deleteByMailAddress($_POST['game_id'], $_POST['email']);
        
            $api = new LineApi();
            $api->cancel_notify($participant, $gameInfo['title'], $gameInfo['game_date']);
        }
    }
}

if(empty($errMsg)) {
    $title = 'キャンセル完了';
    $msg = '予約のキャンセルが完了しました';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/complete.php');
} else {
    if(isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $mode = 'login';
    } else {
        $email = '';
        $mode = 'guest';
    }
    $gameId = $_POST['game_id'];
    $title = 'キャンセル';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/cancelForm.php');
}
?>