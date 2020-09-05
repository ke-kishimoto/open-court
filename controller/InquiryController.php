<?php
namespace controller;
require_once('./model/entity/Inquiry.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/GameInfoDao.php');
require_once('./controller/api/LineApi.php');
use dao\InquiryDao;
use dao\GameInfoDao;
use entity\Inquiry;
use controller\LineApi;

class InquiryController extends BaseController
{

    public function inquiry() {
        parent::userHeader();
        
        $gameInfoDao = new GameInfoDao();
        $gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'));

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'お問い合わせ';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/inquiry.php');
        include('./view/common/footer.php');

    }

    public function inquiryComplete() {
        parent::userHeader();

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
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
        include('./view/common/footer.php');
    }
}

