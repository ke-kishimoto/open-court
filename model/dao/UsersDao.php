<?php
namespace dao;

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
      (admin_flg, email, name, password, occupation, sex, remark, register_date) 
      values(:admin_flg, :email, :name, :password, :occupation, :sex, :remark, :register_date)';
      $prepare = $this->pdo->prepare($sql);
      $prepare->bindValue(':admin_flg', $users->adminFlg, PDO::PARAM_INT);
      $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
      $prepare->bindValue(':name', $users->name, PDO::PARAM_STR);
      $prepare->bindValue(':password', $users->password, PDO::PARAM_STR);
      $prepare->bindValue(':occupation', $users->occupation, PDO::PARAM_INT);
      $prepare->bindValue(':sex', $users->sex, PDO::PARAM_INT);
      $prepare->bindValue(':remark', $users->remark, PDO::PARAM_STR);
      $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
      $prepare->execute();
    }

    // ユーザー情報の更新
    public function update(Users $users) {
        $sql = 'update users set name = :name
        , email = :email, occupation = :occupation, sex = :sex, remark = :remark 
        , update_date = :update_date
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $users->email, PDO::PARAM_STR);
        $prepare->bindValue(':name', $users->name, PDO::PARAM_STR);
        $prepare->bindValue(':occupation', $users->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $users->sex, PDO::PARAM_INT);
        $prepare->bindValue(':remark', $users->remark, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $users->id, PDO::PARAM_INT);
        $prepare->execute();

    }

    public function updatePass(int $id, string $password) {
        $sql = 'update users set password = :password 
        , update_date = :update_date
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':password', $password, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function delete(int $id) {
        // $sql = 'delete from users where id = :id';
        $sql = 'update users set delete_flg = 9 where id = :id';
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
        $sql = 'select * from users where email = :email and delete_flg = 1';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    // IDによるユーザーの取得
    public function getUserById(int $id) {
        $sql = 'select * from users where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
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

