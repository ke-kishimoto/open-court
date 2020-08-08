<?php 
require_once('../../model/dao/DetailDao.php');
use dao\DetailDao;

$detailDao = new DetailDao();
$participantList = $detailDao->getParticipantList($_GET['gameid'], $_GET['occupation'], $_GET['sex'], $_GET['waiting_flg']);

$gameId = $_GET['gameid'];
include('./Header.php');  
$title = '参加者登録完了';
include('../../view/admin/head.php');
include('../../view/admin/header.php');
include('../../view/admin/participantNameList.php');
?>