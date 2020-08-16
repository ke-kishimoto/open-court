<?php 
require_once('../../model/dao/DetailDao.php');
require_once('./Header.php');  
use dao\DetailDao;

$detailDao = new DetailDao();
$participantList = $detailDao->getParticipantList($_GET['gameid'], $_GET['occupation'], $_GET['sex'], $_GET['waiting_flg']);

$gameId = $_GET['gameid'];
$title = '参加者名一覧';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/participantNameList.php');
include('../../view/admin/common/footer.php');
?>