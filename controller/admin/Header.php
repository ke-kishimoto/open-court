<?php 
require_once('../../model/dao/ConfigDao.php');
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
?>