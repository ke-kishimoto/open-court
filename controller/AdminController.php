<?php
namespace controller;

use controller\BaseController;

class AdminController extends BaseController
{

    public function index() {
        parent::adminHeader();

        $title = 'イベントカレンダー';
        include('./view/common/head.php');
        include('./view/eventList.php');

    }

    public function config() {
        parent::adminHeader();

        // CSRF対策
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'システム設定';
        include('./view/common/head.php');
        include('./view/config.php');
    }

    public function notice()
    {
        parent::adminHeader();

         // CSRF対策
         $toke_byte = openssl_random_pseudo_bytes(16);
         $csrf_token = bin2hex($toke_byte);
         $_SESSION['csrf_token'] = $csrf_token;
 
         $title = 'お知らせ登録';
         include('./view/common/head.php');
         include('./view/notice.php');
    }

    public function userList() {
        parent::adminHeader();

        $title = 'ユーザー一覧';
        include('./view/common/head.php');
        include('./view/userList.php');
    }

    public function inquiryList() {
        parent::adminHeader();

        $title = 'お問い合わせ一覧';
        include('./view/common/head.php');
        include('./view/inquiryList.php');
    }


    public function signOut() {
        parent::adminHeader();

        session_destroy();
        header('Location: /index');

    }

    public function participantInfo() {
        parent::adminHeader();

        // CSRF対策
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '参加者情報登録';
        include('./view/common/head.php');
        include('./view/participantInfo.php');
    }

    public function eventTemplate() 
    {
        parent::adminHeader();

        // CSRF対策
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'テンプレート登録';
        include('./view/common/head.php');
        include('./view/eventTemplate.php');
    }

    public function segmentDelivery()
    {
        parent::adminHeader();

        // CSRF対策
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'セグメント配信';
        include('./view/common/head.php');
        include('./view/segmentDelivery.php');
    }
}
