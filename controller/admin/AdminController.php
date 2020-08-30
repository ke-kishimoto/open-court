<?php
namespace controller\admin;

require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/UsersDao.php');
require_once('./model/dao/GameInfoDao.php');
use dao\GameInfoDao;
use dao\ConfigDao;
use dao\UsersDao;
use dao\InquiryDao;

class AdminController {

    public function index() {

        $gameInfoDao = new GameInfoDao();
        require_once("./controller/calendar.php"); 
        // require_once('./Header.php');  
        $gameInfoList = $gameInfoDao->getGameInfoList($year, $month);
        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        $title = 'イベントカレンダー';
        $adminFlg = '1';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/calendar.php');
        include('./view/eventList.php');
        include('./view/admin/common/footer.php');

    }

    public function userList() {

        $userDao = new UsersDao();
        $userList = $userDao->getUserList();

        $title = 'ユーザー一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/userList.php');
        include('./view/admin/common/footer.php');
    }

    public function inquiryList() {
        $inquiryDao = new InquiryDao();
        $inquiryList = $inquiryDao->getInquiryList();

        $title = 'お問い合わせ一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/inquiryList.php');
        include('./view/admin/common/footer.php');
    }

    public function signin() {
        $configDao = new ConfigDao();
        $config = $configDao->getConfig(1);
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
        include('./view/admin/common/footer.php');
    }

    public function signinCheck() {
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
            include('./view/admin/common/footer.php');

        } else {
            header('Location: /admin/admin/index');
        }
    }

    public function signOut() {
        session_destroy();
        header('Location: /admin/admin/index');

    }

}
