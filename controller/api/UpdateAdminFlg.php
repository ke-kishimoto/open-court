<?php

header('Content-type: application/json; charset= UTF-8');
require_once('../../model/dao/UsersDao.php');
use dao\UsersDao;

$userDao = new UsersDao();
// キャンセル待ちフラグの更新
$userDao->updateAdminFlg($_POST['id']);

$user = $userDao->getUserById($_POST['id']);


if($user['admin_flg'] == '1') {
    $info['authority_name'] = '管理者';
} else {
    $info['authority_name'] = '一般';
}

echo json_encode($info);
