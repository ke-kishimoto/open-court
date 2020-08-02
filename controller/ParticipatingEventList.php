<?php
require_once('./header.php');  
require_once('../model/dao/DetailDao.php');
use dao\DetailDao;

if(isset($_SESSION['user'])) {
    $detailDao = new DetailDao();
    $eventList = $detailDao->getEventListByEmail($_SESSION['user']['email'], date('Y-m-d'));

    $title = '参加イベントリスト';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/participatingEventList.php');

} else {
    header('Location: ./index.php');
}
?>