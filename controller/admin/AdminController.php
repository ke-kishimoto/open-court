<?php
namespace controller\admin;

use controller\BaseController;
use dao\GameInfoDao;
use dao\ConfigDao;
use dao\UsersDao;
use dao\InquiryDao;

class AdminController extends BaseController
{

    public function index() {
        parent::adminHeader();

        $gameInfoDao = new GameInfoDao();
        // 現在の年月を取得 
        // $year = date('Y');
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
        // $month = date('n');
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $lastmonth = $month == 1 ? 12 : $month - 1;
        $nextmonth = $month == 12 ? 1 : $month + 1;
        // $lastmonth = date('n',strtotime('-1 month'));
        // $nextmonth = date('n',strtotime('+1 month'));
        $pre_year = $month == 1 ? $year - 1 : $year;
        $next_year = $month == 12 ? $year + 1 : $year;

        // 月末日を取得
        $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
        
        $calendar = array();
        $j = 0;
        // 今日の日付
        $today = (int)date('d');

        // 月末日までループ
        for ($i = 1; $i < $last_day + 1; $i++) {
        
            // 曜日を取得
            $firstDayWeek = date('w', mktime(0, 0, 0, $month, $i, $year));
        
            // 1日の場合
            if ($i == 1) {
        
                // 1日目の曜日までをループ
                for ($s = 1; $s <= $firstDayWeek; $s++) {
        
                    // 前半に空文字をセット
                    $calendar[$j]['day'] = '';
                    $calendar[$j]['link'] = false;
                    $calendar[$j]['today'] = '';
                    $calendar[$j]['weekName'] = '';
                    $j++;
        
                }
        
            }
        
            $info = $gameInfoDao->getGameInfoListByDate($year . '-' . $month . '-' . $i);
            // 配列に日付をセット
            $calendar[$j]['day'] = $i;

            // 今日かどうか
            if($i === $today) {
                $calendar[$j]['today'] = 'today';
            } else {
                $calendar[$j]['today'] = '';
            }

            // 曜日判定
            if($j % 7 === 0) {
                $calendar[$j]['weekName'] = 'sunday';
            }elseif($j % 7 === 6) {
                $calendar[$j]['weekName'] = 'saturday';
            } else {  
                $calendar[$j]['weekName'] = '';
            }

            // イベント有無の判定
            if (!empty($info)) {
                $calendar[$j]['link'] = true;
                $calendar[$j]['info'] = $info;
            } else {
                $calendar[$j]['link'] = false;
            }
            $j++;
        
            // 月末日の場合
            if ($i == $last_day) {
        
                // 月末日から残りをループ
                for ($e = 1; $e <= 6 - $firstDayWeek; $e++) {
        
                    // 後半に空文字をセット
                    $calendar[$j]['day'] = '';
                    $calendar[$j]['link'] = false;
                    $calendar[$j]['today'] = '';
                    $calendar[$j]['weekName'] = '';
                    $j++;
                }
            }
        }
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
        parent::adminHeader();

        $userDao = new UsersDao();
        $userList = $userDao->getUserList();

        $title = 'ユーザー一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/userList.php');
        include('./view/admin/common/footer.php');
    }

    public function inquiryList() {
        parent::adminHeader();

        $inquiryDao = new InquiryDao();
        $inquiryList = $inquiryDao->getInquiryList();

        $title = 'お問い合わせ一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/inquiryList.php');
        include('./view/admin/common/footer.php');
    }

    public function signin() {
        parent::adminHeader();

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
            include('./view/admin/common/footer.php');

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
