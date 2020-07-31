<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\Users;

class SignUpDao {

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

    // 参加者登録
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

    // Usersのidの取得
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

    // ユーザー取得（メールアドレス）
    public function getUserByEmail(string $email) {
        $sql = 'select * from users 
        where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }
}
