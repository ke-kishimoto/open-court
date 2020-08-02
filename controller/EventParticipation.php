<?php
// session_start();
// 参加予約の登録
require_once('../model/entity/Participant.php');
require_once('../model/entity/Companion.php');
require_once('../model/dao/DetailDao.php');
require_once('../model/dao/ConfigDao.php');
require_once('../model/dao/CompanionDao.php');
require_once('../controller/api/LineApi.php');
require_once('./header.php');
use entity\Participant;
use entity\Companion;
use dao\DetailDao;
use dao\CompanionDao;

$errMsg = '';
if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $detailDao = new DetailDao();
    // メールアドレスによる重複チェック
    if($_POST['email'] !== '' && $detailDao->existsCheck($_POST['game_id'], $_POST['email'])) {
        $errMsg = '既に登録済みのため登録できません。';
    } else {
        // キャンセル待ちになるかどうかのチェック
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
        try {
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();
            $detailDao->insert($detail);
        
            // 同伴者の登録
            if($_POST['companion'] > 0) {
                $id = $detailDao->getParticipantId($detail);
                $companionDao = new CompanionDao();
                $companionDao->setPdo($detailDao->getPdo());
                for($i = 1; $i <= $_POST['companion']; $i++) {
                    $companion = new Companion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                    $companionDao->insert($companion);
                }
            }
            $detailDao->getPdo()->commit();
        } catch(Exception $ex) {
            $detailDao->getPdo()->rollBack();
        }
        
        // 予約の通知
        $api = new LineApi();
        $api->reserve_notify($detail, $_POST['title'], $_POST['date'], $_POST['companion']);

        // if ($waitingFlg) {
        //     // 上限に達した通知
        //     $api->limit_notify($_POST['title'], $_POST['date'], $detail['limit_number'], $detail['count']);
        // }
    }

    unset($_SESSION['csrf_token']);
    if(empty($errMsg)) {
        $title = 'イベント参加登録完了';
        $msg = 'イベント参加登録が完了しました。';
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/complete.php');
    } else {
        $title = 'イベント参加登録完了';
        $msg = '入力されたメールアドレスで既に登録済みです。';
        include('../view/common/head.php');
        include('../view/common/header.php');
        include('../view/complete.php');
    }
} else {
    header('Location: ./index.php');
}

?>
