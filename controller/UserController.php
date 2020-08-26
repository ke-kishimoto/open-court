<?php 
namespace controller;

require_once('./model/dao/UsersDao.php');
require_once('./model/dao/DefaultCompanionDao.php');
require_once('./controller/header.php');
use dao\UsersDao;
use dao\DefaultCompanionDao;
use entity\Users;
use entity\DefaultCompanion;
use Exception;

class UserController {

    // サインイン（ログイン）
    public function signIn() {
        session_start();
        $title = 'ログイン';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signIn.php');
        include('./view/common/footer.php');
    }

    // ログインチェック
    public function signInCheck() {
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

        if($errMsg !== '') {
            $title = 'ログイン';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/signIn.php');
            include('./view/common/footer.php');
        } else {
            header('Location: /index.php');
        }
    }

    // サインアップ
    public function signUp() {
        session_start();
        $user = array(
            'id' => ''
            , 'name' => ''
            , 'occupation' => '1'
            , 'sex' => '1'
            , 'email' => ''
            , 'password' => ''
            , 'remark' =>''
        );
        $companions = [];
        $title = '新規登録';
        $mode = 'new';
        $id = '';
  
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signUp.php');
        include('./view/common/footer.php');
    }

    // ユーザー登録
    public function signUpComplete() {
        // session_start();
        
        // 更新処理
        if (!empty($_POST)) {
            $errMsg = '';
            $usersDao = new UsersDao();

            if($_POST['mode'] == 'new') {
                //パスワードチェック
                if (($_POST['password']) != ($_POST['rePassword'])) {
                    $errMsg = 'パスワードが異なっています。';            
                }
                // メールアドレスによる重複チェック
                if($usersDao->existsCheck($_POST['email'])){
                    $errMsg = '入力されたメールアドレスは既に登録済みです。';
                }
            }

            if(empty($errMsg)){
                $adminFlg = 0;
                if ($_POST['mode'] == 'new') {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                } else {
                    $password = '';
                }
                $users = new Users(
                    $adminFlg
                    , $_POST['email']
                    , $_POST['name']
                    , $password
                    , $_POST['occupation']
                    , $_POST['sex']
                    , $_POST['remark']
                );
            
                try {
                    // トランザクション開始
                    $usersDao->getPdo()->beginTransaction();
                    $defaultCompanionDao = new DefaultCompanionDao();
                    if($_POST['mode'] == 'new') {
                        // 新規登録
                        $usersDao->insert($users);
                    } else {
                        // 更新
                        $users->id = $_POST['id'];
                        $usersDao->update($users);
                        // 同伴者の削除
                        $defaultCompanionDao->deleteByuserId($users->id);
                    }
            
                    // 同伴者の登録
                    if($_POST['companion'] > 0) {
                        $id = $usersDao->getUsersId($users);
                        $defaultCompanionDao->setPdo($usersDao->getPdo());
                        for($i = 1; $i <= $_POST['companion']; $i++) {
                            $defaultCompanion = new DefaultCompanion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                            $defaultCompanionDao->insert($defaultCompanion);
                        }
                    }
                    $usersDao->getPdo()->commit();
            
                } catch(Exception $ex) {
                    $usersDao->getPdo()->rollBack();
                }
            }
        } 
        if(!empty($errMsg)) {
            $title = '新規登録';
            $mode = 'new';
            $user = array(
                'name' => $_POST['name']
                , 'occupation' => $_POST['occupation']
                , 'sex' => $_POST['sex']
                , 'email' => $_POST['email']
                , 'password' => $_POST['password']
                , 'ewPassword' => $_POST['password']
                , 'remark' => $_POST['remark']
            );
            if($_POST['companion'] > 0) {
                for($i = 1; $i <= $_POST['companion']; $i++) {
                    $companions[$i-1] = array(
                        'occupation' => $_POST['occupation-' . $i]
                        , 'sex' => $_POST['sex-' . $i]
                        , 'name' => $_POST['name-' . $i]
                    );   
                }
            } else {
                $companions = [];
            }
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/signup.php');
            include('./view/common/footer.php');
        } else {
            $title = 'ユーザー登録完了';
            $msg = 'ユーザー登録が完了しました。';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/complete.php');
            include('./view/common/footer.php');
        }
    }

    // アカウント編集
    public function edit() {
        session_start();
        if(!empty($_GET) && !empty($_SESSION['user'])) {
            $usersDao = new UsersDao();
            $defultCompanionDao = new DefaultCompanionDao();
            $user = $usersDao->getUserById($_GET['id']);
            $companions = $defultCompanionDao->getDefaultCompanionList($user['id']);
            $title = 'アカウント情報修正';
            $mode = 'update';
            $id = $_GET['id'];
            $passChange = '';
        } else {
            
        }
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signUp.php');
        include('./view/common/footer.php');
        
    }

    // パスワード変更
    public function passwordChange() {
        // require_once('./header.php');

        $title = 'パスワード変更';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/passwordChange.php');
        include('./view/common/footer.php');
    }

    public function passwordChangeComplete() {
        // session_start();
        // require_once('../model/dao/UsersDao.php');
        // require_once('./header.php');

        if (!empty($_POST)) {
            $errMsg = '';
            $usersDao = new UsersDao();

            //パスワードチェック
            if (($_POST['password']) != ($_POST['rePassword'])) {
                $errMsg = '入力されたパスワードが異なります。';
            }

            if(empty($errMsg)){
                $usersDao->updatePass($_SESSION['user']['id'], password_hash($_POST['password'], PASSWORD_DEFAULT));
                $title = 'パスワード変更完了';
                $msg = 'パスワードを変更しました';
                
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/complete.php');
                include('./view/common/footer.php');
            } else {
                $title = 'パスワード変更';
                
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/passwordChange.php');
                include('./view/common/footer.php');
            }
        }
    }

    // サインアウト
    public function signout() {
        // session_start();
        // session_unset();
        session_destroy();
        header('Location: /index.php');
    }

    // 削除（退会）
    public function delete() {
        session_start();
        if(isset($_SESSION['user'])) {
            $usersDao = new UsersDao();
            try {
                // トランザクション開始
                $usersDao->getPdo()->beginTransaction();
                $defaultCompanionDao = new DefaultCompanionDao();
                // 同伴者の削除
                $defaultCompanionDao->deleteByuserId($_SESSION['user']['id']);
                $usersDao->delete($_SESSION['user']['id']);
            
                $usersDao->getPdo()->commit();
            
                // session_unset('user');
                session_destroy();
            
            } catch(Exception $ex) {
                $usersDao->getPdo()->rollBack();
            }
        }
        $title = '退会';
        $msg = '退会処理が完了しました。';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
    }

}
