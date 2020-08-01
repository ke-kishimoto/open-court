<?php 
require_once('../model/dao/ConfigDao.php');
use dao\ConfigDao;
if (!isset($_SESSION['system_title'])) {
    $configDao = new ConfigDao();
    $config = $configDao->getConfig(1);
    $_SESSION['system_title'] = $config['system_title'];
}

if(isset($_SESSION['user']) == null) {
    $loginClass = 'hidden';
    $noLoginClass = '';
    $userName = 'ゲスト';
    $id = '';
} else {
    $loginClass = '';
    $noLoginClass = 'hidden';
    $userName = $_SESSION['user']['name'];
    $id = $_SESSION['user']['id'];
}
?>
