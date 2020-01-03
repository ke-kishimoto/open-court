<?php
require_once(dirname(__FILE__).'/model/entity/Participant.php');
require_once(dirname(__FILE__).'/model/dao/DetailDao.php');

$detail = new Participant(
    $_POST['game_id']
    , $_POST['occupation']
    , $_POST['sex']
    , $_POST['name']
);

$detailDao = new DetailDao();
$detailDao->insert($detail);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録完了</title>
</head>
<body>
    <p>参加登録完了しました。</p>
    <p><a href="index.php">TOPに戻る</a></p>
</body>
</html>
