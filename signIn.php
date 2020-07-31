<?php
// session_start();
// ログイン

$btnClass = 'btn btn-primary';
$btnLiteral = 'ログイン';

// // 自動ログインは未実装
// require_once(dirname(__FILE__).'/model/entity/Users.php');
// require_once(dirname(__FILE__).'/model/entity/DefaultCompanion.php');
// require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
// require_once(dirname(__FILE__).'/model/dao/DefaultCompanionDao.php');
// use entity\Users;
// use entity\DefaultCompanion;
// use dao\UsersDao;
// use dao\DefaultCompanionDao;

// $limitFlg = false;
// $btnClass = 'btn btn-primary';
// $btnLiteral = 'ログイン';

// if (!empty($_POST)) {
//     $errMsg = '';
//     $UsersDao = new UsersDao();

//     $adminFlg = 0;
//     $users = new Users(
//         $adminFlg
//         , $_POST['email']
//         , ''
//         , $_POST['password']
//         , ''
//         , ''
//         , ''
//     );

//     //存在チェック
//     if($usersDao->existsCheck($_POST['email']) 
//     && $usersDao->comparePassword($users)){

//     }else{
        
//     }

// } 
// session_destroy();
// function console_log( $data ){
//     echo '<script>';
//     echo 'console.log('. json_encode( $data ) .')';
//     echo '</script>';
// }
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログイン</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>

<div>
    <form id="signIn_form" action="controller/SignInController.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            メール
            <input class="form-control" type="email" name="email" required maxlength="50">
        </p>
        <p>
            パスワード
            <input class="form-control" type="password" name="password" required maxlength="50">
        </p>
        <input type="checkbox" id="autoLogin" name="autoLogin" value="true">
        <label for="autoLogin">ログインしたままにする</label><br><br>
        <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
    </form>
</div>
</body>
</html>