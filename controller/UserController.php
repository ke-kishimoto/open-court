<?php 
namespace controller;

use dao\UsersDao;
use dao\DefaultCompanionDao;
use dao\ConfigDao;
use api\MailApi;
use service\UserService;
use Exception;

class UserController extends BaseController
{

    // サインイン（ログイン）
    public function signIn() {
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        // 10桁のランダム英数字
        $state = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $_SESSION['state'] = $state;
        $title = 'ログイン';
        include('./view/common/head.php');
        include('./view/signIn.php');
    }


    // サインアップ
    public function signUp() {
        // 10桁のランダム英数字
        $state = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10);
        $_SESSION['state'] = $state;
        
        $title = '新規登録';
        $mode = 'new';
        $id = '';
  
        include('./view/common/head.php');
        include('./view/signUp.php');
    }

    // アカウント編集
    public function edit() {

        $title = 'アカウント情報修正';
        include('./view/common/head.php');
        include('./view/signUp.php');
        
    }

    // パスワード変更画面への遷移
    public function passwordChange() {

        $title = 'パスワード変更';
        include('./view/common/head.php');
        include('./view/passwordChange.php');
    }

    // 参加イベント一覧
    public function participatingEventList() {

        $title = '参加イベントリスト';
        include('./view/common/head.php');
        include('./view/participatingEventList.php');
    }

    // サインアウト
    public function signout() {
        session_start();
        unset($_SESSION['user']);
        header('Location: /index.php');
    }

    // パスワードを忘れた場合
    public function passwordForget()
    {

        $title = 'パスワードリセット';
        include('./view/common/head.php');
        include('./view/passwordForget.php');
    }

    // LINEでログイン
    public function lineLogin()
    {

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
        include('./view/complete.php');
    }

}
