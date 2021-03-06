<?php 
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
//セッションに'user'が無ければログイン画面へ
if (isset($_SESSION['user']) == null) {
    header('Location: SignIn.php');
    $userName = '管理者';
} else {
    $userName = $_SESSION['user']['name'];
}

?>