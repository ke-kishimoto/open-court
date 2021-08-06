<?php
namespace controller;

use dao\GameInfoDao;
use controller\BaseController;
use controller\EventCalendar;

class EventController extends BaseController
{

    public function index() {
        parent::userHeader();

        // カレンダー処理
        $gameInfoDao = new GameInfoDao();
        // 現在の年月を取得 
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

        $eventCalendar = new EventCalendar($year, $month);

        $title = 'イベントカレンダー';
        $adminFlg = '0';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/calendar.php');
        include('./view/eventList.php');

    }
}

