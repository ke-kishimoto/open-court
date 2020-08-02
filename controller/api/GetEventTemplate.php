<?php

header('Content-type: application/json; charset= UTF-8');

require_once('../../model/dao/EventTemplateDao.php');
use dao\EventTemplateDao;

$eventTemplateDao = new EventTemplateDao();
$eventTemplate = $eventTemplateDao->getEventTemplate(intval($_POST['id']));

echo json_encode($eventTemplate);

?>