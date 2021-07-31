<?php
namespace controller\admin;

use controller\BaseController;
use dao\DetailDao;
use dao\GameInfoDao;
use dao\SalesDao;

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
        
        // 年月の取得
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $lastmonth = $month == 1 ? 12 : $month - 1;
        $nextmonth = $month == 12 ? 1 : $month + 1;
        $lastYear = $month == 1 ? $year - 1 : $year;
        $nextYear = $month == 12 ? $year + 1 : $year;

        $salesDao = new SalesDao();
        $eventList = $salesDao->getMonthSales($year, $month);
        
        $title = '売上管理';
        $adminFlg = '0';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/monthlysales.php');
        include('./view/admin/common/footer.php');
    }

    public function month()
    {
        parent::adminHeader();
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
        $salesDao = new SalesDao();
        $salesMonthList = $salesDao->getYearMonthSales($year);

        $total_cnt = 0;
        $total_amount = 0;
        foreach ($salesMonthList as $month) {
            $total_cnt += (int)$month['cnt'];
            $total_amount += (int)$month['amount'];
        }

        $title = '売上管理';
        $adminFlg = '0';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/monthlysales2.php');
        include('./view/admin/common/footer.php');
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

        $salesDao = new SalesDao();
        $participantList = $salesDao->getSalesDetail($_GET['gameid']);

        $title = '売上管理（イベント単位）';
        $adminFlg = '0';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventsales.php');
        include('./view/admin/common/footer.php');
    }

    public function update()
    {
        parent::adminHeader();

        $errMsg = '';
        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $count = isset($_POST['count']) ? $_POST['count'] : 0;

            $detailDao = new DetailDao();
            for($i = 0; $i < $count; $i++) {
                $p = $detailDao->selectById((int)$_POST["id-{$i}"]);
                $participant = [];
                $participant['id'] = $p['id'];
                $participant['attendance'] = $_POST["attendance-{$i}"];
                $participant['amount'] = (int)$_POST["amount-{$i}"];
                $participant['amount_remark'] = $_POST["amount_remark-{$i}"];
                $detailDao->update($participant);
            }

            $title = '売上更新完了';
            $msg = '売上の更新が完了しました。';
            $adminFlg = '0';
            include('./view/admin/common/head.php');
            include('./view/admin/common/header.php');
            include('./view/admin/complete.php');
            include('./view/admin/common/footer.php');
        } else {
            header('Location: /index.php');
        }
    }

    public function updateExpenses()
    {
        parent::adminHeader();

        $errMsg = '';
        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $count = isset($_POST['count']) ? $_POST['count'] : 0;

            $GameInfoDao = new GameInfoDao();
            for($i = 0; $i < $count; $i++) {
                $p = $GameInfoDao->selectById((int)$_POST["id-{$i}"]);
                $gameInfo = [];
                $gameInfo['id'] = $p['id'];
                $gameInfo['expenses'] = (int)$_POST["expenses-{$i}"];
                $gameInfo['participantnum'] = (int)$_POST["cnt-{$i}"];
                $gameInfo['amount'] = (int)$_POST["amount-{$i}"];
                $GameInfoDao->update($gameInfo);
            }

            $title = '経費更新完了';
            $msg = '経費の更新が完了しました。';
            $adminFlg = '0';
            include('./view/admin/common/head.php');
            include('./view/admin/common/header.php');
            include('./view/admin/complete.php');
            include('./view/admin/common/footer.php');
        } else {
            header('Location: /index.php');
        }
    }

    public function year()
    {
        parent::adminHeader();

        $salesDao = new SalesDao();
        $salesYearList = $salesDao->getYearSales();
        $total_cnt = 0;
        $total_amount = 0;
        foreach ($salesYearList as $year) {
            $total_cnt += (int)$year['cnt'];
            $total_amount += (int)$year['amount'];
        }

        $title = '売上管理（年単位）';
        $adminFlg = '0';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/yearlysales.php');
        include('./view/admin/common/footer.php');
    }
}