<?php
session_start();
require_once('../../model/entity/Participant.php');
require_once('../../model/dao/CompanionDao.php');
require_once('../../model/dao/DetailDao.php');
require_once('../../model/entity/Companion.php');
require_once('./Header.php');  
use entity\Companion;
use entity\Participant;
use dao\DetailDao;
use dao\CompanionDao;


if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $detailDao = new DetailDao();
    $companionDao = new CompanionDao();
    $companionDao->setPdo($detailDao->getPdo());
    try {
        $detailDao->getPdo()->beginTransaction();
        if($detailDao->limitCheck($_POST['game_id'], 1)) {
            $waitingFlg = 1;
        } else {
            $waitingFlg = 0;
        }

        // 同伴者を削除しておく
        if ($_POST['id'] !== '') {
            $companionDao->deleteByparticipantId($_POST['id']);
        }
        if (isset($_POST['register'])) {
            $participant = new Participant(
                $_POST['game_id']
                , $_POST['occupation']
                , $_POST['sex']
                , $_POST['name']
                , $_POST['email']
                , $waitingFlg
                , $_POST['remark']
            );
            if($_POST['id'] !== '') {
                $participant->id = $_POST['id']; // IDはコンストラクタにないので固定でセット
                $detailDao->update($participant);
                $id = $participant->id;
            } else {
                $detailDao->insert($participant);
                $id = $detailDao->getParticipantId($participant);
            }
            // 同伴者の登録
            if($_POST['companion'] > 0) {
                for($i = 1; $i <= $_POST['companion']; $i++) {
                    $companion = new Companion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                    $companionDao->insert($companion);
                }
            }
        } else {
            $detailDao->delete($_POST['id']);
        }
        $detailDao->getPdo()->commit();
    } catch(Exception $ex) {
        $detailDao->getPdo()->rollBack();
    }
    
    unset($_SESSION['csrf_token']);
} else {
    header('Location: ./index.php');
}

$title = '参加者登録完了';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
$msg = '参加者の登録が完了しました。';
include('../../view/admin/complete.php');
include('../../view/admin/common/footer.php');
?>
