<?php
namespace controller;

class NoticeController extends BaseController {
    public function index() {
        parent::userHeader();

        $title = 'お知らせ一覧';
        include('./view/common/head.php');
        include('./view/news.php');
    }

    public function detail()
    {
        parent::userHeader();

        $title = 'お知らせ詳細';
        include('./view/common/head.php');
        include('./view/newsdetail.php');
    }
}
