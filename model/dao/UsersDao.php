<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\Users;

class UsersDao {

    private $pdo;
    public function __construct() {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ユーザー登録
    public function insert(Users $users) {
      $sql = 'insert into users 
      (admin_flg, email, name, password, occupation, sex, remark) 
      values(:admin_flg, :email, :name, :password, :occupation, :sex, :remark)';
      $prepare = $this->pdo->prepare($sql);
      $prepare->bindValue(':admin_flg', $users->adminFlg, PDO::PARAM_INT);
      $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
      $prepare->bindValue(':name', $users->name, PDO::PARAM_STR);
      $prepare->bindValue(':password', $users->password, PDO::PARAM_STR);
      $prepare->bindValue(':occupation', $users->occupation, PDO::PARAM_INT);
      $prepare->bindValue(':sex', $users->sex, PDO::PARAM_INT);
      $prepare->bindValue(':remark', $users->remark, PDO::PARAM_STR);
      $prepare->execute();
    }

    // ユーザーのidの取得
    public function getUsersId(Users $users) {
        $sql = 'select max(id) id
                from users 
                where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);

        $prepare->execute();
        $info = $prepare->fetch();
        return $info['id'];
    }

    // メールアドレスによる存在チェック
    public function existsCheck(string $email) {
        $users = new Users('',$email, '', '', '', '', '');
        $id = $this->getUsersId($users);
        if (isset($id)) {
            return true;
        }
        return false;
    }

    // パスワードのチェック
    // パスワードのハッシュ化は「password_hash()」で行っているのですが、
    // 毎回ランダムなハッシュ値になってしまうようで、
    // 比較には「password_verify()」を使用しなければならないみたいです。
    // 参照：https://qiita.com/rana_kualu/items/3ef57485be1103362f56
    public function comparePassword(Users $users) {
        $sql = 'select password
                from users 
                where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
        $prepare->execute();
        $info = $prepare->fetch();
        if(password_verify($users->email, $info['password']){
            return true;
        else
            return false;
    }

    // ログイン
    public function login(Users $users) {
        $UsersDao->existsCheck($users->email)
        $UsersDao->comparePassword($users)
        $sql = 'select *
                from users 
                where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
        $prepare->execute();
        $users = $prepare->fetch();


    }
}
