<?php
require_once('../model/entity/Participant.php');
require_once('../model/dao/DetailDao.php');
use entity\Participant;
use dao\DetailDao;

session_start();

if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

    $detailDao = new DetailDao();
    if (isset($_POST['register'])) {
        $participant = new Participant(
            $_POST['game_id']
            , $_POST['occupation']
            , $_POST['sex']
            , $_POST['name']
            , $_POST['companion']
            , $_POST['remark']
        );
        if($_POST['id'] !== '') {
            $participant->id = $_POST['id']; // IDはコンストラクタにないので固定でセット
            $detailDao->update($participant);
        } else {
            $detailDao->insert($participant);
        }
    } else {
        $detailDao->delete($_POST['id']);
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
    <title>変更完了</title>
</head>
<body>
    <p>参加者情報の変更完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</body>
</html>