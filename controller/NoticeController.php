<?php
namespace controller;

use dao\NoticeDao;

class NoticeController extends BaseController {
    public function index() {
        parent::userHeader();

        $noticeDao = new NoticeDao();
        $prepare = $noticeDao->query("
        select * 
        , date_format(register_date, '%Y-%m-%d') date
        from notice
        where delete_flg = 1
        order by id desc
        ");
        $noticeList = $prepare->fetchAll();

        $title = 'お知らせ一覧';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/news.php');
        include('./view/common/footer.php');
    }

    public function detail()
    {
        parent::userHeader();

        $noticeDao = new NoticeDao();
        $prepare = $noticeDao->query("
        select * 
        , date_format(register_date, '%Y-%m-%d') date
        from notice
        where id = :id
        ", 
        [
            'id' => $_GET['id']
        ]);
        $notice = $prepare->fetch();

        $title = 'お知らせ詳細';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/newsdetail.php');
        include('./view/common/footer.php');
    }
}
