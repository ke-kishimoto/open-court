<?php
namespace controller;
use dao\TroubleReportDao;
use api\LineApi;

class TroubleReportController extends BaseController
{

    public function index() {
        parent::userHeader();
        
        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '改善目安箱';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/troubleReport.php');

    }

    public function complete() {
        parent::userHeader();

        $errMsg = '';
        if(isset($_POST)) {
            $troubleReportDao = new TroubleReportDao();
            $troubleReport = [];
            $troubleReport['name'] = $_POST['name'];
            $troubleReport['category'] = (int)$_POST['category'];
            $troubleReport['title'] = $_POST['title'];
            $troubleReport['content'] = $_POST['content'];
            $troubleReport['status_flg'] = 0;

            $troubleReportDao->insert($troubleReport);

            // LINE通知用に参加者情報とイベント情報を取得
            $api = new LineApi();
            $api->troubleReport($troubleReport);
    
        }

        $title = 'お問い合わせ完了';
        $msg = 'お問い合わせが完了しました';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
    }
}

