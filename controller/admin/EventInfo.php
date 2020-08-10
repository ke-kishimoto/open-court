<?php
session_start();
require_once('../../model/dao/GameInfoDao.php');
require_once('../../model/dao/DetailDao.php');
require_once('../../model/dao/EventTemplateDao.php');
use dao\EventTemplateDao;
use dao\GameInfoDao;
use dao\DetailDao;

// テンプレ一覧
$eventTemplateDao = new EventTemplateDao();
$eventTemplateList = $eventTemplateDao->getEventTemplateList();

$gameInfo = null;
$gameInfoDao = new GameInfoDao();
$templateAreaClass = 'hidden';
$participantDisp = '';
// 試合情報取得
if (isset($_GET['id'])) {
    $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
}
if (empty($gameInfo)) {
    // 新規の場合
    //    header('Location: index.php');
    $gameInfo = array(
        'id' => ''
        , 'title' => ''
        , 'short_title' => ''
        , 'game_date' => ''
        , 'start_time' => ''
        , 'end_time' => ''
        , 'place' => ''
        , 'limit_number' => 0
        , 'detail' => ''
    );
    $templateAreaClass = '';
    $participantDisp = 'hidden';
}
// 参加者情報取得
$participantList = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
}

$detail = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $detail = $detailDao->getDetail($gameInfo['id']);
}

if(empty($detail)) {
    $detail = array('cnt' => 0
        , 'limit_number' => 0
        , 'sya_women' => 0
        , 'sya_men' => 0
        , 'dai_women' => 0
        , 'dai_men' => 0
        , 'kou_women' => 0
        , 'kou_men' => 0
        , 'waiting_cnt' => 0
    );
}

// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

include('./Header.php');  
$title = 'イベント情報登録';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/eventInfo.php');
include('../../view/admin/common/footer.php');
?>
