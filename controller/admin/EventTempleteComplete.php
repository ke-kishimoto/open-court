<?php
// session_start();
require_once('../../model/entity/EventTemplate.php');
require_once('../../model/dao/EventTemplateDao.php');
use entity\EventTemplate;
use dao\EventTemplateDao;


if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $msg = '';
    if (isset($_POST['register'])) {
        // 登録・修正''
        $msg = '登録';
        $eventTemplate = new EventTemplate(
            $_POST['template_name']
            , $_POST['title']
            , $_POST['short_title']
            , $_POST['place']
            , $_POST['limit_number']
            , $_POST['detail']
        );
        
        $eventTemplateDao = new EventTemplateDao();
        
        if($_POST['id'] == '' || isset($_POST['new'])) {
            $eventTemplateDao->insert($eventTemplate);
        } else {
            $eventTemplate->id = $_POST['id'];
            $eventTemplateDao->update($eventTemplate);
        }
    } else {
        $eventTemplateDao = new EventTemplateDao();
        if($_POST['id'] != '') {
            $msg = '削除';
            $eventTemplateDao->delete($_POST['id']);
        }
    }
    unset($_SESSION['csrf_token']);
} else {
    header('Location: ./index.php');
}

include('./Header.php');  
$title = 'テンプレート登録完了';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
$msg = 'テンプレートの更新が完了しました。';
include('../../view/admin/complete.php');
?>