<?php
namespace controller;

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
        include('./view/troubleReport.php');

    }

}

