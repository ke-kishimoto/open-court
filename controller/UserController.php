<?php 
namespace controller;

use dao\UsersDao;
use dao\DefaultCompanionDao;
use dao\DetailDao;
use dao\ConfigDao;
use api\MailApi;
// use api\LineApi;
use service\UserService;
use Exception;

class UserController extends BaseController
{

    // サインイン（ログイン）
    public function signIn() {
        parent::userHeader();
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        // 10桁のランダム英数字
        $state = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $_SESSION['state'] = $state;
        $title = 'ログイン';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signIn.php');
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
        } else {
            header('Location: /index.php');
        }
    }

    // サインアップ
    public function signUp() {
        parent::userHeader();
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        // 10桁のランダム英数字
        $state = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $_SESSION['state'] = $state;
        $user = [
            'id' => ''
            , 'name' => ''
            , 'occupation' => '1'
            , 'sex' => '1'
            , 'email' => ''
            , 'line_id' => ''
            , 'password' => ''
            , 'remark' =>''
        ];
        $companions = [];
        $title = '新規登録';
        $mode = 'new';
        $id = '';
  
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/signUp.php');
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
                if(isset($_POST['email']) && $usersDao->existsCheck($_POST['email'])){
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
                $users = [];
                $users['email'] = $_POST['email'] ?? '';
                $users['name'] = $_POST['name'];
                $users['password'] =  $password;
                $users['occupation'] = $_POST['occupation'];
                $users['sex'] = $_POST['sex'];
                $users['remark'] = $_POST['remark'];
                $users['admin_flg'] = 0;
            
                try {
                    // トランザクション開始
                    $usersDao->getPdo()->beginTransaction();
                    $defaultCompanionDao = new DefaultCompanionDao();
                    if($_POST['mode'] == 'new') {
                        // 新規登録
                        $usersDao->insert($users);
                    } else {
                        // 更新
                        $users['id'] = $_POST['id'];
                        $usersDao->update($users);
                        // 同伴者の削除
                        $defaultCompanionDao->deleteByuserId($users['id']);
                    }
            
                    // 同伴者の登録
                    if($_POST['companion'] > 0) {
                        $id = $usersDao->getUsersId($users);
                        $defaultCompanionDao->setPdo($usersDao->getPdo());
                        for($i = 1; $i <= $_POST['companion']; $i++) {
                            $defaultCompanion = [];
                            $defaultCompanion['user_id'] = $id; 
                            $defaultCompanion['occupation'] = $_POST['occupation-' . $i];
                            $defaultCompanion['sex'] = $_POST['sex-' . $i];
                            $defaultCompanion['name'] = $_POST['name-' . $i];
                            
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
        } else {
            $title = 'ユーザー登録完了';
            $msg = 'ユーザー登録が完了しました。';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/complete.php');
        }
    }

    // アカウント編集
    public function edit() {
        parent::userHeader();
        if(!empty($_GET) && !empty($_SESSION['user'])) {
            $usersDao = new UsersDao();
            $defultCompanionDao = new DefaultCompanionDao();
            $user = $usersDao->selectById((int)$_GET['id']);
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
        
    }

    // パスワード変更画面への遷移
    public function passwordChange() {
        parent::userHeader();

        $title = 'パスワード変更';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/passwordChange.php');
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
            } else {
                $title = 'パスワード変更';
                
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/passwordChange.php');
            }
        }
    }

    // 参加イベント一覧
    public function participatingEventList() {
        parent::userHeader();

        if(isset($_SESSION['user'])) {
            $detailDao = new DetailDao();
            if(isset($_SESSION['user']['email']) && !empty($_SESSION['user']['email'])) {
                $eventList = $detailDao->getEventListByEmail($_SESSION['user']['email'], date('Y-m-d'));
            } elseif(isset($_SESSION['user']['line_id']) && !empty($_SESSION['user']['line_id'])) {
                $eventList = $detailDao->getEventListByLineId($_SESSION['user']['line_id'], date('Y-m-d'));
            }
        
            $title = '参加イベントリスト';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/participatingEventList.php');
        
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
                $usersDao->updateDeleteFlg($_SESSION['user']['id']);
            
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

    // パスワードを忘れた場合
    public function passwordForget()
    {
        parent::userHeader();

        $title = 'パスワードリセット';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/passwordForget.php');
    }

    // パスワードリセット
    public function passReset()
    {
        parent::userHeader();
        $userDao = new UsersDao();
        $email = $_POST['email'] ?? '';
        $user = $userDao->getUserByEmail($email);

        if(!$user) {
            $errMsg = '入力されたメールアドレスによる登録がありません。';
            $title = 'パスワードリセット';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/passwordForget.php');
        } else {
            // 8文字で適当なパスワードを生成
            $pass = substr(base_convert(md5(uniqid()), 16, 36), 0, 8);
            $mailApi = new MailApi();
            $responseCode = $mailApi->passreset_mail($email, $pass);
            if($responseCode == 202 || $responseCode == 201) {
                // ハッシュ化されたパスワード
                $password = password_hash($pass, PASSWORD_DEFAULT);
                $userDao->updatePass((int)$user['id'], $password);

                $title = 'パスワードリセット完了';
                $msg = 'パスワードリセットのメールを送信しました。ご確認ください。';
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/complete.php');

            } else {
                $errMsg = 'メールの送信に失敗しました。';
                $title = 'パスワードリセット';
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/passwordForget.php');
            }

        }
    }
    // LINEでログイン
    public function lineLogin()
    {
        parent::userHeader();

        // アクセストークン取得用認可コード
        $code = $_GET['code'];
        $state = $_GET['state'];
        // CORS対策
        if($state !== $_SESSION['state']) {
            unset($_SESSION['state']);
            // ユーザーを取得せずにトップ画面に遷移
            header('Location: /index.php');
            return;
        }
        unset($_SESSION['state']);

        $service = new UserService();
        $user = $service->lineLogin($code);

        // 名前・職種・性別が設定されていない場合は登録画面に遷移
        if(empty($user['name']) || empty($user['occupation']) || empty($user['sex'])) {
            $title = '新規登録';
            $id = $user['id'];
            // $companions = [];
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/lineSignUp.php');
        } else {
            // 存在するなら、そのままセッションに保存してログイン状態に持っていきたい
            $_SESSION['user'] = $user;
            header('Location: /index.php');
        }
    }
    // LINEで初回ログイン時
    public function lineSignupComplete()
    {
        parent::userHeader();
        $usersDao = new UsersDao();

        $user = [];
        $user['occupation'] = $_POST['occupation'];
        $user['sex'] = $_POST['sex'];
        $user['remark'] = $_POST['remark'];
        try {
            // トランザクション開始
            $usersDao->getPdo()->beginTransaction();
            $defaultCompanionDao = new DefaultCompanionDao();
            // 更新
            $user['id'] = $_POST['id'];
            $usersDao->update($user);
    
            // // 同伴者の登録
            // // LINEで予約の場合は同伴者を考慮しにくいので、一旦保留
            // if($_POST['companion'] > 0) {
            //     $id = $usersDao->getUsersId($user);
            //     $defaultCompanionDao->setPdo($usersDao->getPdo());
            //     for($i = 1; $i <= $_POST['companion']; $i++) {
            //         // $defaultCompanion = new DefaultCompanion($id, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
            //         $defaultCompanion = new DefaultCompanion();
            //         $defaultCompanion['user_id'] = $id; 
            //         $defaultCompanion['occupation'] = $_POST['occupation-' . $i];
            //         $defaultCompanion['sex'] = $_POST['sex-' . $i];
            //         $defaultCompanion['name'] = $_POST['name-' . $i];
            //         $defaultCompanionDao->insert($defaultCompanion);
            //     }
            // }
            $usersDao->getPdo()->commit();
    
        } catch(Exception $ex) {
            $usersDao->getPdo()->rollBack();
        }
        $user = $usersDao->selectById($user['id']);
        $_SESSION['user'] = $user;

        $title = 'ユーザー登録完了';
        $msg = 'ユーザー登録が完了しました。';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/complete.php');
    }

}
