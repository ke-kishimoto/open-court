<?php
namespace controller;

use dao\GameInfoDao;
use controller\BaseController;

class EventController extends BaseController
{

    public function index() {

        $title = 'イベントカレンダー';
        $adminFlg = '0';
        include('./view/common/head.php');
        include('./view/eventList.php');

    }
}

