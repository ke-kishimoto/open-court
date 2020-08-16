<?php
session_start();
require_once('../../model/entity/GameInfo.php');
require_once('../../model/dao/GameInfoDao.php');
require_once('./Header.php');
use entity\GameInfo;
use dao\GameInfoDao;


if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $msg = '';
    if (isset($_POST['register'])) {
        // 登録・修正''
        $msg = '登録';
        $gameInfo = new GameInfo(
            $_POST['title']
            , $_POST['short_title']
            , $_POST['game_date']
            , $_POST['start_time']
            , $_POST['end_time']
            , $_POST['place']
            , $_POST['limit_number']
            , $_POST['detail']
        );
        
        $gameInfoDao = new GameInfoDao();
        if($_POST['id'] == '') {
            $gameInfoDao->insert($gameInfo);
        } else {
            $gameInfo->id = $_POST['id'];
            $gameInfoDao->update($gameInfo);
        }
    } else {
        $gameInfoDao = new GameInfoDao();
        if($_POST['id'] != '') {
            $msg = '削除';
            try {
                $gameInfoDao->getPdo()->beginTransaction();
                $gameInfoDao->delete($_POST['id']);
                $gameInfoDao->getPdo()->commit();
            }catch (Exception $ex) {
                $gameInfoDao->getPdo()->rollBack();
            }
        }
    }
    unset($_SESSION['csrf_token']);
} else {
    header('Location: ./index.php');
}

$title = 'イベント登録完了';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/eventComplete.php');
include('../../view/admin/common/footer.php');
?>
