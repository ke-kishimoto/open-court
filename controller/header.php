<?php 
require_once('../model/dao/ConfigDao.php');
use dao\ConfigDao;
$configDao = new ConfigDao();
$config = $configDao->getConfig(1);
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

if(isset($_SESSION['user']) == null) {
    $loginClass = 'hidden';
    $noLoginClass = '';
    $userName = 'ゲスト';
    $id = '';
    $adminMenuFlg = '0';
} else {
    $loginClass = '';
    $noLoginClass = 'hidden';
    $userName = $_SESSION['user']['name'];
    $id = $_SESSION['user']['id'];
    if($_SESSION['user']['admin_flg'] == '1') {
        $adminMenuFlg = '1';
    } else {
        $adminMenuFlg = '0';
    }
}
?>
