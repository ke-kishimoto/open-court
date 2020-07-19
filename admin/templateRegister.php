<?php
require_once('../model/entity/EventTemplate.php');
require_once('../model/dao/EventTemplateDao.php');
use entity\EventTemplate;
use dao\EventTemplateDao;

session_start();

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
        if($_POST['id'] == '' || $_POST['new'] == true) {
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>完了</title>
</head>
<body>
    <p><?php echo $msg ?>完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>