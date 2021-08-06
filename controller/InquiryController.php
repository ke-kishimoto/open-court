<?php
namespace controller;
use dao\GameInfoDao;
use service\InquiryService;

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

    }

    public function inquiryComplete() {
        parent::userHeader();

        $errMsg = '';
        if(isset($_POST)) {
            if (isset($_POST["csrf_token"]) && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
                $gameId = (int)$_POST['game_id'];
                $inquiry = [];
                $inquiry['game_id'] = $gameId;
                $inquiry['name'] = $_POST['name'];
                $inquiry['email'] = $_POST['email'];
                $inquiry['content'] = $_POST['content'];
                $inquiry['status_flg'] = 0;
                if(isset($_SESSION['user'])) {
                    $inquiry['line_id'] = $_SESSION['user']['line_id'] ?? '';
                }
                $inquiry['update_date'] = null;
                
                $service = new InquiryService();
                $service->sendInquiry($inquiry);
            
                unset($_SESSION['csrf_token']);

            }
    
        }

        $title = 'お問い合わせ完了';
        $msg = 'お問い合わせが完了しました';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
    }
}

