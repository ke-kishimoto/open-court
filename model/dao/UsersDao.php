<?php
namespace dao;

use PDO;

class UsersDao extends BaseDao
{
    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'users';
    }

    public function updatePass(int $id, string $password) 
    {
        $sql = 'update users set 
        password = :password 
        , update_date = :update_date
        where id = :id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':password', $password, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // ユーザーのidの取得
    public function getUsersId($user) 
    {
        $sql = "select max(id) id
                from users 
                where email = :email
                and delete_flg = '1'";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':email', $user['email'], PDO::PARAM_STR);

        $prepare->execute();
        $info = $prepare->fetch();
        return $info['id'];
    }

    public function selectAll(int $deleteFlg = 0)
    {
        return $this->getUserList();
    }

    public function selectById(int $id)
    {
        $sql = "select 
        *
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
            when delete_flg = 1 then '有効'
            when delete_flg = 9 then '削除済み' 
            else ''
          end status
        , case 
            when admin_flg = 1 then '管理者'
            else '一般'
          end authority_name
        from users 
        where id = :id
        order by id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function getUserList()
    {
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
            when delete_flg = 1 then '有効'
            when delete_flg = 9 then '削除済み' 
            else ''
          end status
        , case 
            when admin_flg = 1 then '管理者'
            else '一般'
          end authority_name
        from users 
        order by id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    // メールアドレスによる存在チェック
    public function existsCheck(string $email) 
    {
        $user = [];
        $user['email'] = $email;
        $id = $this->getUsersId($user);
        if (isset($id)) {
            return true;
        }
        return false;
    }

    // ユーザー取得（メールアドレス）
    public function getUserByEmail(string $email) 
    {
        $sql = 'select * from users where email = :email and delete_flg = 1';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    // ユーザー取得(LINE ID)
    public function getUserByLineId(String $lineId)
    {
        $sql = 'select * from users where line_id = :lineId and delete_flg = 1';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':lineId', $lineId, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function updateFlg(int $id)
    {
        $this->updateAdminFlg($id);
    }

    // 権限更新
    public function updateAdminFlg(int $id) 
    {
        $sql = 'update users set admin_flg = 
                case 
                  when admin_flg = 1 then 0 
                  else 1
                end
                where id = :id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

        
    }
}  

