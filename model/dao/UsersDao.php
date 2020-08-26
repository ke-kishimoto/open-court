<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');
require_once(dirname(__FILE__).'/../entity/Users.php');

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

    // ユーザー情報の更新
    public function update(Users $users) {
        $sql = 'update users set name = :name
        , email = :email, occupation = :occupation, sex = :sex, remark = :remark 
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
        $prepare->bindValue(':name', $users->name, PDO::PARAM_STR);
        $prepare->bindValue(':occupation', $users->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $users->sex, PDO::PARAM_INT);
        $prepare->bindValue(':remark', $users->remark, PDO::PARAM_STR);
        $prepare->bindValue(':id', $users->id, PDO::PARAM_INT);
        $prepare->execute();

    }

    public function updatePass(int $id, string $password) {
        $sql = 'update users set password = :password where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':password', $password, PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function delete(int $id) {
        $sql = 'delete from users where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
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

    public function getUserList(){
        $sql = "select 
        id
        , name 
        , email
        , occupation
        , case 
            when occupation =  1 then '社会人'
            when occupation =  2 then '大学・専門学校'
            when occupation =  3 then '高校'
            else 'その他' 
          end occupation_name
        , sex
        , case
            when sex = 1 then '男性'
            when sex = 2 then '女性'
          end sex_name 
        , case 
            when admin_flg = 1 then '管理者'
            else '一般'
          end authority_name
        from users 
        order by id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->execute();
        return $prepare->fetchAll();
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
        $sql = 'select * from users where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function getUserById(int $id) {
        $sql = 'select * from users where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
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
        if(password_verify($users->email, $info['password'])){
            return true;
        } else
            return false;
    }
    
    // ログイン
    public function login(Users $users) {
        $this->existsCheck($users->email);
        $this->comparePassword($users);
        $sql = 'select *
                from users 
                where email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
        $prepare->execute();
        $users = $prepare->fetch();
    }

    // 権限更新
    public function updateAdminFlg(int $id) {
        $sql = 'update users set admin_flg = 
                case 
                  when admin_flg = 1 then 0 
                  else 1
                end
                where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        
    }
}  

