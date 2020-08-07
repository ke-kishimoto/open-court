<?php

header('Content-type: application/json; charset= UTF-8');

require_once('../../model/dao/UsersDao.php');
use dao\UsersDao;

$userDao = new UsersDao();
$user = $userDao->getUserById(intval($_POST['id']));

echo json_encode($user);

?>