<?php
session_start();
require_once('../../model/dao/UsersDao.php');
require_once('./Header.php');
use dao\UsersDao;

$signUpDao = new UsersDao();

$user = $signUpDao->getUserByEmail($_POST['email']);

if($user) {
    if(password_verify($_POST['password'], $user['password']) && ($user['admin_flg'] == '1')) {
        $_SESSION['user'] = $user;
    } else {
        $errMsg = 'メールアドレス、またはパスワードが異なります';
    }
} else {
    $errMsg = 'メールアドレス、またはパスワードが異なります';
}

if(isset($errMsg)) {
    $title = 'ログイン';
    include('../../view/admin/common/head.php');
    include('../../view/admin/common/header.php');
    include('../../view/admin/signIn.php');
    include('../../view/admin/common/footer.php');

} else {
    header('Location: ./index.php');
}

?>