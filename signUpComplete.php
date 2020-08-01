<?php
// session_start();
// 新規登録・アカウント情報修正
require_once(dirname(__FILE__).'/model/entity/Users.php');
require_once(dirname(__FILE__).'/model/entity/DefaultCompanion.php');
require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
require_once(dirname(__FILE__).'/model/dao/DefaultCompanionDao.php');
use entity\Users;
use entity\DefaultCompanion;
use dao\UsersDao;
use dao\DefaultCompanionDao;

$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';


// 更新処理
if (!empty($_POST)) {
    $errMsg = '';
    $usersDao = new UsersDao();

    if($_POST['mode'] == 'new') {
        //パスワードチェック
        if (($_POST['password']) != ($_POST['rePassword'])) {
            $errMsg = 'パスワード(再入力)が同じでありません';
        // メールアドレスによる重複チェック
        }else if($usersDao->existsCheck($_POST['email'])){
            $errMsg = '既に登録済みです';
        }
    }

    if(empty($errMsg)){
        $adminFlg = 0;
        if ($_POST['mode'] == 'new') {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            $password = '';
        }
        $users = new Users(
            $adminFlg
            , $_POST['email']
            , $_POST['name']
            , $password
            , $_POST['occupation']
            , $_POST['sex']
            , $_POST['remark']
        );
    
        try {
            // トランザクション開始
            $usersDao->getPdo()->beginTransaction();
            $defaultCompanionDao = new DefaultCompanionDao();
            if($_POST['mode'] == 'new') {
                // 新規登録
                $usersDao->insert($users);
            } else {
                // 更新
                $users->id = $_POST['id'];
                $usersDao->update($users);
                // 同伴者の削除
                $defaultCompanionDao->deleteByuserId($users->id);
            }
    
            // 同伴者の登録
            if($_POST['companion'] > 0) {
                $id = $usersDao->getUsersId($users);
                $defaultCompanionDao->setPdo($usersDao->getPdo());
                for($i = 1; $i <= $_POST['companion']; $i++) {
                    $defaultCompanion = new DefaultCompanion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                    $defaultCompanionDao->insert($defaultCompanion);
                }
            }
            $usersDao->getPdo()->commit();
    
            $errMsg = '登録完了';
        } catch(Exception $ex) {
            $usersDao->getPdo()->rollBack();
        }
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
    <p>登録が完了しました。</p>
    <p><a href="index.php">イベント一覧に戻る</a></p>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    
</script>
</body>
</html>