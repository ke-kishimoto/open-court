<?php
session_start();
require_once('../model/entity/Inquiry.php');
require_once('../model/dao/InquiryDao.php');
require_once('../model/dao/ConfigDao.php');
require_once('../controller/api/LineApi.php');
require_once('./header.php');
use dao\InquiryDao;
use dao\GameInfoDao;
use entity\Inquiry;
use controller\LineApi;

$errMsg = '';
if(isset($_POST)) {
    $inquiryDao = new InquiryDao();
    $gameId = (int)$_POST['game_id'];
    $inquiry = new Inquiry($gameId, $_POST['name'], $_POST['email'], $_POST['content'], 0, date('Y-m-d'), null);
    $inquiryDao->insert($inquiry);

    $inquiry->gameTitle = '';
    if($gameId) {
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->getGameInfo($gameId);
        $inquiry->gameTitle = $gameInfo['title'];
    }
    // LINE通知用に参加者情報とイベント情報を取得
    $api = new LineApi();
    $api->inquiry($inquiry);
    
}

$title = 'お問い合わせ完了';
$msg = 'お問い合わせが完了しました';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/complete.php');
include('../view/common/footer.php');
