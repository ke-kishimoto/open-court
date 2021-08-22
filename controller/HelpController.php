<?php
namespace controller;

class HelpController extends BaseController {
    public function privacyPolicy() {

        $title = 'プライバシーポリシー';
        include('./view/common/head.php');
        include('./view/privacyPolicy.php');
    }

    public function notice() {

        $title = 'お知らせ一覧';
        include('./view/common/head.php');
        include('./view/news.php');
    }

    public function noticeDetail()
    {

        $title = 'お知らせ詳細';
        include('./view/common/head.php');
        include('./view/newsdetail.php');
    }

    public function troubleReport() {
        
        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '改善目安箱';
        include('./view/common/head.php');
        include('./view/troubleReport.php');

    }

    public function inquiry() {
        
        // CSFR対策
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'お問い合わせ';
        include('./view/common/head.php');
        include('./view/inquiry.php');

    }
}
