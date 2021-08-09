<?php
namespace api;
use dao\DefaultCompanionDao;
use dao\UsersDao;
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

    public function userRegist()
    {
        header('Content-type: application/json; charset= UTF-8');

        $data = json_decode(file_get_contents('php://input'), true);

        $usersDao = new UsersDao();
        
        // メールアドレスによる重複チェック
        $errMsg = '';
        if(isset($data['user']['email']) && $usersDao->existsCheck($data['user']['email'])){
            $errMsg = '入力されたメールアドレスは既に登録済みです。';
            echo json_encode(['errMsg' => $errMsg]);
            return;
        }

        if ((int)$data['editId'] === -1) {
            $password = password_hash($data['user']['password'], PASSWORD_DEFAULT);
        } else {
            $password = '';
        }
        $user = [];
        $user['email'] = $data['user']['email'] ?? '';
        $user['name'] = $data['user']['name'];
        $user['password'] =  $password;
        $user['occupation'] = $data['user']['occupation'];
        $user['sex'] = $data['user']['sex'];
        $user['remark'] = $data['user']['remark'];
        $user['admin_flg'] = 0;

        try {
            // トランザクション開始
            $usersDao->getPdo()->beginTransaction();
            $defaultCompanionDao = new DefaultCompanionDao();
            if((int)$data['editId'] === -1) {
                // 新規登録
                $usersDao->insert($user);
            } else {
                // 更新
                $users['id'] = $data['editId'];
                $usersDao->update($user);
                // 同伴者の削除
                $defaultCompanionDao->deleteByuserId($user['id']);
            }
    
            // 同伴者の登録
            if($data['companion'] > 0) {
                $id = $usersDao->getUsersId($user);
                $defaultCompanionDao->setPdo($usersDao->getPdo());
                for($i = 0; $i < count($data['companion']); $i++) {
                    $defaultCompanion = [];
                    $defaultCompanion['user_id'] = $id; 
                    $defaultCompanion['occupation'] = $data['companion'][$i]['occupation'];
                    $defaultCompanion['sex'] = $data['companion'][$i]['sex'];
                    $defaultCompanion['name'] = $data['companion'][$i]['name'];
                    
                    $defaultCompanionDao->insert($defaultCompanion);
                }
            }
            $usersDao->getPdo()->commit();
    
        } catch(Exception $ex) {
            $usersDao->getPdo()->rollBack();
            $errMsg = 'エラーが発生したため更新できませんでした。';
        }

        echo json_encode(['errMsg' => $errMsg]);

    }
}