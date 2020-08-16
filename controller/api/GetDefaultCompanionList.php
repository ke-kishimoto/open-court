<?php

header('Content-type: application/json; charset= UTF-8');

require_once('../../model/dao/DefaultCompanionDao.php');
use dao\DefaultCompanionDao;

$defaultCompanionDao = new DefaultCompanionDao();
$companionList = $defaultCompanionDao->getDefaultCompanionList(intval($_POST['id']));

echo json_encode($companionList);
