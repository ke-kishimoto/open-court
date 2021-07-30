<?php
namespace controller\admin;

use controller\BaseController;
use dao\NoticeDao;
use entity\Notice;

class NoticeController extends BaseController 
{
    public function index()
    {
        parent::adminHeader();

        $noticeDao = new NoticeDao();
        $noticeList = $noticeDao->selectAll(1);

         // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
         $toke_byte = openssl_random_pseudo_bytes(16);
         $csrf_token = bin2hex($toke_byte);
         // 生成したトークンをセッションに保存します
         $_SESSION['csrf_token'] = $csrf_token;
 
         $title = 'お知らせ登録';
         include('./view/admin/common/head.php');
         include('./view/admin/common/header.php');
         include('./view/admin/notice.php');
         include('./view/admin/common/footer.php');

    }

    public function regist()
    {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $noticeDao = new NoticeDao();
            if (isset($_POST['register'])) {
                // $notice = new Notice();
                // $notice->title = $_POST['title'];
                // $notice->content = $_POST['content'];
                $notice = [];
                $notice['title'] = $_POST['title'];
                $notice['content'] = $_POST['content'];

                if($_POST['id'] == '' || isset($_POST['new'])) {
                    $noticeDao->insert($notice);
                } else {
                    $notice['id'] = $_POST['id'];
                    $noticeDao->update($notice);
                }

            } else {
                if($_POST['id'] != '') {
                    $noticeDao->updateDeleteFlg($_POST['id']);
                }
            }


            unset($_SESSION['csrf_token']);
        } else {
            header('Location: ./index.php');
        }

        $title = 'お知らせ登録完了';
        $msg = 'お知らせの更新が完了しました。';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/complete.php');
        include('./view/admin/common/footer.php');


    }
}