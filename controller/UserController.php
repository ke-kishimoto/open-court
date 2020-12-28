<?php 
namespace controller;

use dao\UsersDao;
use dao\DefaultCompanionDao;
use dao\DetailDao;
use entity\Users;
use entity\DefaultCompanion;
use Exception;

class UserController extends BaseController
{

    // サインイン（ログイン）
    public function signIn() {
        parent::userHeader();
        $title = 'ログイン';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signIn.php');
        include('./view/common/footer.php');
    }

    // ログインチェック
    public function signInCheck() {
        parent::userHeader();
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
        parent::userHeader();
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
        parent::userHeader();
        
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
                $users = new Users();
                $users->email = $_POST['email'];
                $users->name = $_POST['name'];
                $users->password =  $password;
                $users->occupation = $_POST['occupation'];
                $users->sex = $_POST['sex'];
                $users->remark = $_POST['remark'];
                $users->adminFlg = 0;
            
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
                            // $defaultCompanion = new DefaultCompanion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                            $defaultCompanion = new DefaultCompanion();
                            $defaultCompanion->userId = $id; 
                            $defaultCompanion->occupation = $_POST['occupation-' . $i];
                            $defaultCompanion->sex = $_POST['sex-' . $i];
                            $defaultCompanion->name = $_POST['name-' . $i];
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
        parent::userHeader();
        if(!empty($_GET) && !empty($_SESSION['user'])) {
            $usersDao = new UsersDao();
            $defultCompanionDao = new DefaultCompanionDao();
            $user = $usersDao->getUserById((int)$_GET['id']);
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

    // パスワード変更画面への遷移
    public function passwordChange() {
        parent::userHeader();

        $title = 'パスワード変更';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/passwordChange.php');
        include('./view/common/footer.php');
    }

    // パスワード変更処理
    public function passwordChangeComplete() {
        parent::userHeader();
        
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

    // 参加者リスト一覧
    public function participatingEventList() {
        parent::userHeader();

        if(isset($_SESSION['user'])) {
            $detailDao = new DetailDao();
            $eventList = $detailDao->getEventListByEmail($_SESSION['user']['email'], date('Y-m-d'));
        
            $title = '参加イベントリスト';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/participatingEventList.php');
            include('./view/common/footer.php');
        
        } else {
            header('Location: /index.php');
        }
    }

    // サインアウト
    public function signout() {
        session_start();
        // session_unset();
        // session_destroy();
        unset($_SESSION['user']);
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
