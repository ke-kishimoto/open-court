<?php
session_start();
require_once('../../model/entity/Config.php');
require_once('../../model/dao/ConfigDao.php');
use entity\Config;
use dao\ConfigDao;


if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
    // 登録・修正
    $config = new Config(
        $_POST['id']
        , $_POST['line_token']
        , $_POST['system_title']
    );
        
    $configDao = new ConfigDao();
    $configDao->update($config);

    unset($_SESSION['csrf_token']);

    // header('Location: ./');
} else {
    header('Location: ./index.php');
}

include('./Header.php');  
$title = 'システム設定完了';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
$msg = 'システム設定が完了しました。';
include('../../view/admin/complete.php');
?>
