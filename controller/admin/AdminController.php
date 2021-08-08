<?php
namespace controller\admin;

use controller\BaseController;
use dao\GameInfoDao;
use dao\ConfigDao;
use dao\UsersDao;
use dao\InquiryDao;
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

    public function signin() {
        parent::adminHeader();

        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        if(!isset($_SESSION)){
            session_start();
        }
        if (!isset($_SESSION['system_title'])) {
            $_SESSION['system_title'] = $config['system_title'];
        }
        if ($config['bg_color'] == 'white') {
            $bgColor = 'bg-color-white';
        } elseif ($config['bg_color'] == 'orange') {
            $bgColor = 'bg-color-orange';
        } else {
            $bgColor = 'bg-color-white';
        }
        $userName = '管理者';
        // include('./Header.php');  
        $title = 'ログイン';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/signIn.php');
    }

    public function signinCheck() {
        parent::adminHeader();

        $signUpDao = new UsersDao();

        $user = $signUpDao->getUserByEmail($_POST['email']);

        if($user) {
            if(password_verify($_POST['password'], $user['password']) && ($user['admin_flg'] == '1')) {
                $_SESSION['user'] = $user;
            } else {
                $errMsg = 'メールアドレス、またはパスワードが異なります';
            }
        } else {
            $errMsg = 'メールアドレス、またはパスワードが異なります';
        }

        if(isset($errMsg)) {
            $title = 'ログイン';
            include('./view/admin/common/head.php');
            include('./view/admin/common/header.php');
            include('./view/admin/signIn.php');
        } else {
            header('Location: /admin/admin/index');
        }
    }

    public function signOut() {
        parent::adminHeader();

        session_destroy();
        header('Location: /admin/admin/index');

    }

}
