<?php
namespace controller;

use controller\BaseController;

class SalesController extends BaseController
{
    public function index()
    {
        parent::adminHeader();

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;
        
        $title = '売上管理';
        include('./view/common/head.php');
        include('./view/monthlysales.php');
    }

    public function month()
    {
        parent::adminHeader();

        $title = '売上管理';
        include('./view/common/head.php');
        include('./view/monthlysales2.php');
    }

    public function detail()
    {
        parent::adminHeader();

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '売上管理（イベント単位）';
        include('./view/common/head.php');
        include('./view/eventsales.php');
    }

    
    public function year()
    {
        parent::adminHeader();

        $title = '売上管理（年単位）';
        include('./view/common/head.php');
        include('./view//yearlysales.php');
    }
}