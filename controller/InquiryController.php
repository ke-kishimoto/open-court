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
        include('./view/common/footer.php');

    }

    public function inquiryComplete() {
        parent::userHeader();

        $errMsg = '';
        if(isset($_POST)) {
            if (isset($_POST["csrf_token"]) && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
                $gameId = (int)$_POST['game_id'];
                // $inquiry = new Inquiry($gameId, $_POST['name'], $_POST['email'], $_POST['content'], 0, date('Y-m-d'), null);
                // $inquiry = new Inquiry();
                // $inquiry->gameId = $gameId;
                // $inquiry->name = $_POST['name'];
                // $inquiry->email = $_POST['email'];
                // $inquiry->content = $_POST['content'];
                // $inquiry->statusFlg = 0;
                $inquiry = [];
                $inquiry['game_id'] = $gameId;
                $inquiry['name'] = $_POST['name'];
                $inquiry['email'] = $_POST['email'];
                $inquiry['content'] = $_POST['content'];
                $inquiry['status_flg'] = 0;
                if(isset($_SESSION['user'])) {
                    $inquiry['line_id'] = $_SESSION['user']['line_id'] ?? '';
                }
                // $inquiry->registerDate = date('Y-m-d');
                $inquiry['update_date'] = null;
                
                $service = new InquiryService();
                $service->sendInquiry($inquiry);
                // $inquiryDao = new InquiryDao();
                // $inquiryDao->insert($inquiry);
                
                // $inquiry->gameTitle = '';
                // if($inquiry->gameId) {
                //     $gameInfoDao = new GameInfoDao();
                //     $gameInfo = $gameInfoDao->selectById($inquiry->gameId);
                //     $inquiry->gameTitle = $gameInfo['title'];
                // }

                // // LINE通知用に参加者情報とイベント情報を取得
                // $api = new LineApi();
                // $api->inquiry($inquiry);
    
                // // メール送信
                // if(!empty($inquiry->email)) {
                //     $mailApi = new MailApi();
                //     $mailApi->inquiry_mail($inquiry);
                // }

                unset($_SESSION['csrf_token']);

            }
    
        }

        $title = 'お問い合わせ完了';
        $msg = 'お問い合わせが完了しました';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
        include('./view/common/footer.php');
    }
}

