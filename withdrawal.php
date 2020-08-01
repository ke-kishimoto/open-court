<?php
require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
require_once(dirname(__FILE__).'/model/dao/DefaultCompanionDao.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;

if(isset($_SESSION['user'])) {

    $usersDao = new UsersDao();
    try {
        // トランザクション開始
        $usersDao->getPdo()->beginTransaction();
        $defaultCompanionDao = new DefaultCompanionDao();
        // 同伴者の削除
        $defaultCompanionDao->deleteByuserId($_SESSION['user']['id']);
        $usersDao->delete($_SESSION['user']['id']);
    
        $usersDao->getPdo()->commit();
    
        // session_unset('user');
        session_destroy();
    
    } catch(Exception $ex) {
        $usersDao->getPdo()->rollBack();
    }
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>

<div>
    <p>退会しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    
</script>
</body>
</html>