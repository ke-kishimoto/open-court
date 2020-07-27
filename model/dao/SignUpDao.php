<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');
require_once(dirname(__FILE__).'/../entity/Users.php');

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

    // 参加者idの取得
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
}
