<?php
require_once('../../model/dao/UsersDao.php');
require_once('./Header.php');  
use dao\UsersDao;

$userDao = new UsersDao();
$userList = $userDao->getUserList();

$title = 'ユーザー一覧';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/userList.php');
include('../../view/admin/common/footer.php');
?>