<?php
session_start();
// 新規登録・アカウント情報修正
require_once('../model/entity/Users.php');
require_once('../model/entity/DefaultCompanion.php');
require_once('../model/dao/UsersDao.php');
require_once('../model/dao/DefaultCompanionDao.php');
require_once('./header.php');
use entity\Users;
use entity\DefaultCompanion;
use dao\UsersDao;
use dao\DefaultCompanionDao;

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
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/signup.php');
    include('../view/common/footer.php');
} else {
    $title = 'ユーザー登録完了';
    $msg = 'ユーザー登録が完了しました。';
    include('../view/common/head.php');
    include('../view/common/header.php');
    include('../view/complete.php');
    include('../view/common/footer.php');
}

?>
