<?php
session_start();
require_once('../../model/entity/Config.php');
require_once('../../model/dao/ConfigDao.php');
require_once('./Header.php');

use entity\Config;
use dao\ConfigDao;


if (isset($_POST["csrf_token"]) 
 && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
    // 登録・修正
    $config = new Config(
        $_POST['id']
        , $_POST['line_token']
        , $_POST['system_title']
        , $_POST['bg_color']
        , ''  // img_logo
        , $_POST['waiting_flg_auto_update']
    );
        
    $configDao = new ConfigDao();
    $configDao->update($config);

    unset($_SESSION['csrf_token']);

    // header('Location: ./');
} else {
    header('Location: ./index.php');
}

$title = 'システム設定完了';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
$msg = 'システム設定が完了しました。';
include('../../view/admin/complete.php');
include('../../view/admin/common/footer.php');
?>
