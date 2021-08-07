<?php
namespace api;
use dao\DefaultCompanionDao;
use ReflectionClass;
use Exception;

class UserApi
{
    public function getDefaultCompanion()
    {
        header('Content-type: application/json; charset= UTF-8');

        $dao = new DefaultCompanionDao();
        $data = $dao->getDefaultCompanionList($_POST['id'] ?? 0);
        echo json_encode($data);
    }
}