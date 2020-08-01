<?php 
require_once('../model/entity/Participant.php');
require_once('../model/dao/DetailDao.php');
require_once('../model/dao/ConfigDao.php');
require_once('../model/dao/UsersDao.php');
require_once('../controller/Api.php');
use dao\DetailDao;
use dao\UsersDao;
use dao\GameInfoDao;
use entity\Participant;

if(isset($_POST)) {
    $detailDao = new DetailDao();
    // LINE通知用に参加者情報とイベント情報を取得
    $participant = new Participant($_POST['game_id'], 0, 0, '', $_POST['email'], 0, '');
    $id = $detailDao->getParticipantId($participant);
    $msg = '';
    if ($id == null)  {
        $_SESSION['errMsg'] = '入力されたメールアドレスによる登録がありませんでした。';
        header('Location: ./cancelForm.php');
    } else {
        if(isset($_POST['password']) && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $usersDao = new UsersDao();
            $user = $usersDao->getUserById($userId);
            if(!password_verify($_POST['password'], $user['password'])) {
                $_SESSION['errMsg'] = 'パスワードが異なります';
                header('Location: ./cancelForm.php');
            }
        }
        if(!isset($msg)) {
            $participant = $detailDao->getParticipant($id);
            $gameInfoDao = new GameInfoDao();
            $gameInfo = $gameInfoDao->getGameInfo($_POST['game_id']);
        
            $rowCount = $detailDao->deleteByMailAddress($_POST['game_id'], $_POST['email']);
        
            $api = new Api();
            $api->cancel_notify($participant, $gameInfo['title'], $gameInfo['game_date']);
            $msg = '予約のキャンセルが完了しました。';
        }
    }
}
$title = 'キャンセル完了';

include('./header.php');

include('../view/head.php');
include('../view/header.php');
include('../view/cancelComplete.php');
?>