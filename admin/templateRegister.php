<?php
// session_start();
require_once('../model/entity/EventTemplate.php');
require_once('../model/dao/EventTemplateDao.php');
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>完了</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
    <?php include('./header.php') ?>
    <p><?php echo $msg ?>完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>