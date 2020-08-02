<?php
session_start();
require_once('../model/dao/GameInfoDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
require_once('../model/dao/DetailDao.php');
require_once('./header.php');
use dao\GameInfoDao;
use dao\DefaultCompanionDao;
use dao\DetailDao;

$gameInfo = null;
$limitFlg = false;
$btnLiteral = '登録';
$pastEvent = false;
$gameInfoDao = new GameInfoDao();
// 試合情報取得
if (isset($_GET['id'])) {
    $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
    $detailDao = new DetailDao();
    $limitFlg = $detailDao->limitCheck($gameInfo['id'], 0);
    $detail = $detailDao->getDetail($gameInfo['id']);
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
    if($limitFlg) {
        $btnClass = 'btn btn-warning';
        $btnLiteral = 'キャンセル待ちとして登録';
    }
    // イベント日が過去の場合は登録フォームを隠す
    date_default_timezone_set('Asia/Tokyo');
    if ($gameInfo['game_date'] < date('Y-m-d')) {
        $pastEvent = true;
    }
}

if (empty($gameInfo)) {
    header('Location: index.php');
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

// CSFR対策
// session_start();

// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
// 生成したトークンをセッションに保存します
$_SESSION['csrf_token'] = $csrf_token;

if (isset($_SESSION['user'])) {
    $occupation = $_SESSION['user']['occupation'];
    $sex = $_SESSION['user']['sex'];
    $defaultCompanionDao = new DefaultCompanionDao();
    $companions = $defaultCompanionDao->getDefaultCompanionList($_SESSION['user']['id']);

} else {
    $occupation = null;
    $sex = null;
    $companions = [];
}

$title = 'イベント詳細';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/detail.php');

?>
