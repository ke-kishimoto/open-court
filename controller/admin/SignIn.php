<?php
session_start();
require_once('../../model/dao/ConfigDao.php');
use dao\ConfigDao;
$configDao = new ConfigDao();
$config = $configDao->getConfig(1);
if(!isset($_SESSION)){
    session_start();
}
if (!isset($_SESSION['system_title'])) {
    $_SESSION['system_title'] = $config['system_title'];
}
if ($config['bg_color'] == 'white') {
    $bgColor = 'bg-color-white';
} elseif ($config['bg_color'] == 'orange') {
    $bgColor = 'bg-color-orange';
} else {
    $bgColor = 'bg-color-white';
}
$userName = '管理者';
// include('./Header.php');  
$title = 'ログイン';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/signIn.php');
include('../../view/admin/common/footer.php');

?>