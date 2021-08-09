<?php
namespace api;
use dao\UsersDao;
use ReflectionClass;
use Exception;

class DataApi
{

    /**
     * @Route("/getLoginUser")
     */
    public function getLoginUser()
    {
        header('Content-type: application/json; charset= UTF-8');
        session_start();

        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            $userDao = new UsersDao();
            $user = $userDao->selectById((int)$_SESSION['user']['id']);
        } else {
            $user = [
                'id' => '', 
                'name' => '', 
                'email' => '', 
                'occupation' => '1', 
                'sex' => '', 
                'remark' => '', 
                'line_id' => '',
                'admin_flg' => '0',
            ];
        }

        echo json_encode($user);
    }

    /**
     * @Route("/selectById") 
     */
    public function selectById()
    {
        header('Content-type: application/json; charset= UTF-8');

        $id = $_POST['id'] ?? 0;
        $rClass = new ReflectionClass("dao\\{$_POST['tableName']}Dao");
        $dao = $rClass->newInstance();
        $data = $dao->selectById($id);
        echo json_encode($data);
    }

    /**
     * @Route("/selectAll") 
     */
    public function selectAll()
    {
        header('Content-type: application/json; charset= UTF-8');

        $rClass = new ReflectionClass("dao\\{$_POST['tableName']}Dao");
        $dao = $rClass->newInstance();
        $data = $dao->selectAll();
        echo json_encode($data);
    }

    /**
     * @Route("/getColumnList")
     */
    public function getColumnList()
    {
        header('Content-type: application/json; charset= UTF-8');

        $rClass = new ReflectionClass("dao\\{$_POST['tableName']}Dao");
        $dao = $rClass->newInstance();
        $columnList = $dao->getColumnList();
        echo json_encode($columnList);
    }

    /**
     * @Route("/updateRecord")
     */
    public function updateRecord()
    {
        session_start();
        header('Content-type: application/json; charset= UTF-8');

        $tableName = '';
        $type = '';
        $entity = [];
        foreach($_POST as $key => $value) {
            if($key === 'tableName') {
                $tableName = $value;
            } elseif($key === 'type') {
                $type = $value;
            } else {
                $entity[$key] = $value;
            }
        }
        $entity['delete_flg'] = 1;
        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        // 更新
        try {
            $method = $rClass->getMethod($type);
            $method->invoke($dao, $entity);
            echo json_encode($entity);
        } catch(Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

    /**
     * @Route("/deleteById")
     */
    public function deleteById()
    {
        header('Content-type: application/json; charset= UTF-8');

        $tableName = $_POST['tableName'] ?? '';
        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        $id = (int)($_POST['id'] ?? '');
        try {
            $dao->delete($id);
            echo json_encode('{}');
        } catch(Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

    /**
     * @Route("/bulkDelete")
     */
    public function bulkDelete()
    {
        header('Content-type: application/json; charset= UTF-8');

        $tableName = $_POST['tableName'] ?? '';
        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        $idList = explode(",", $_POST['idList']) ?? [];
        try {
            $stmt = $dao->bulkDelete($idList);
            echo json_encode('{}');
        } catch(Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

    /**
     * @Route("/updateFlg")
     */
    public function updateFlg()
    {
        header('Content-type: application/json; charset= UTF-8');

        $tableName = $_POST['tableName'] ?? '';
        // ステータスフラグの更新
        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        try {
            $data = $dao->updateFlg((int)$_POST['id']);
            echo json_encode($data);
        } catch(Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

    /**
     * @Route("/getCalData")
     */
    public function getCalData()
    {
        header('Content-type: application/json; charset= UTF-8');

        $tableName = $_POST['tableName'] ?? '';
        $month = (int)($_POST['month'] ?? '');

        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        try {
            $data = $dao->getCalData($month);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

    /**
     * @Route("/getSelectboxList")
     */
    public function getSelectboxList() 
    {
        header('Content-type: application/json; charset= UTF-8');

        $tableName = $_POST['tableName'] ?? '';
        $accountId = $_POST['accountId'] ?? $_SESSION['user']['id'] ?? 0;
        $rClass = new ReflectionClass("dao\\{$tableName}Dao");
        $dao = $rClass->newInstance();
        try {
            $data = $dao->getSelectboxList($accountId);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }
    }

}