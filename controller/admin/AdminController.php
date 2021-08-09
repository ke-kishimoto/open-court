<?php
namespace controller\admin;

use controller\BaseController;
use dao\GameInfoDao;
use dao\ConfigDao;
use controller\EventCalendar;

class AdminController extends BaseController
{

    public function index() {
        parent::adminHeader();

        $gameInfoDao = new GameInfoDao();
        // 現在の年月を取得 
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

        $eventCalendar = new EventCalendar($year, $month);

        $title = 'イベントカレンダー';
        $adminFlg = '1';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/calendar.php');
        include('./view/eventList.php');

    }

    public function config() {
        parent::adminHeader();

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'システム設定';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/config.php');
    }

    public function notice()
    {
        parent::adminHeader();

         // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
         $toke_byte = openssl_random_pseudo_bytes(16);
         $csrf_token = bin2hex($toke_byte);
         // 生成したトークンをセッションに保存します
         $_SESSION['csrf_token'] = $csrf_token;
 
         $title = 'お知らせ登録';
         include('./view/admin/common/head.php');
         include('./view/admin/common/header.php');
         include('./view/admin/notice.php');

    }

    public function userList() {
        parent::adminHeader();

        $title = 'ユーザー一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/userList.php');
    }

    public function inquiryList() {
        parent::adminHeader();

        $title = 'お問い合わせ一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/inquiryList.php');
    }


    public function signOut() {
        parent::adminHeader();

        session_destroy();
        header('Location: /admin/admin/index');

    }

    public function participantNameList() {
        parent::adminHeader();

        $gameId = $_GET['gameid'];
        $title = '参加者名一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/participantNameList.php');
    }

    public function participantInfo() {
        parent::adminHeader();

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '参加者情報登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/participantInfo.php');
    }

    public function eventTemplate() 
    {
        parent::adminHeader();

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'テンプレート登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventTemplate.php');
    }

    public function eventInfo() 
    {
        parent::adminHeader();

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'イベント情報登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventInfo.php');
    }
}
