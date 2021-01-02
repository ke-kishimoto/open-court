<?php
namespace controller\admin;

use controller\BaseController;
use dao\SalesDao;

class SalesController extends BaseController
{
    public function index()
    {
        parent::adminHeader();
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
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/admin/monthlysales.php');
        include('./view/common/footer.php');
    }

    public function detail()
    {
        parent::adminHeader();

        $salesDao = new SalesDao();
        $participantList = $salesDao->getSalesDetail($_GET['gameid']);

        $title = '売上管理（イベント単位）';
        $adminFlg = '0';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/admin/eventsales.php');
        include('./view/common/footer.php');
    }
}