<?php
namespace api;
use dao\DefaultCompanionDao;
use dao\UsersDao;
use dao\ConfigDao;
use ReflectionClass;
use Exception;

class UserApi
{

    public function changePassword()
    {
        header('Content-type: application/json; charset= UTF-8');

        session_start();
        $userDao = new UsersDao();
        $userDao->updatePass($_SESSION['user']['id'], password_hash($_POST['password'], PASSWORD_DEFAULT));

        echo json_encode(['errMsg' => '']);
    }

    // ログインチェック
    public function signInCheck() {

        header('Content-type: application/json; charset= UTF-8');

        session_start();
        $signUpDao = new UsersDao();

        $user = $signUpDao->getUserByEmail($_POST['email']);

        $errMsg = '';
        if($user) {
            if(password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errMsg = 'メールアドレス、またはパスワードが異なります';
            }
        } else {
            $errMsg = 'メールアドレス、またはパスワードが異なります';
        }

        echo json_encode(['errMsg' => $errMsg]);
    }

    // LINEログイン用のパラメータ取得
    public function getLineParam()
    {
        header('Content-type: application/json; charset= UTF-8');

        session_start();
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        // 10桁のランダム英数字
        $state = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $_SESSION['state'] = $state;

        $data = [
            'state' => $state,
            'clientId' => $config['client_id']
        ];
        echo json_encode($data);
    }

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
        if ((int)$data['editId'] === -1) {
            if(isset($data['user']['email']) && $usersDao->existsCheck($data['user']['email'])){
                $errMsg = '入力されたメールアドレスは既に登録済みです。';
                echo json_encode(['errMsg' => $errMsg]);
                return;
            }
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
                $user['id'] = $data['editId'];
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