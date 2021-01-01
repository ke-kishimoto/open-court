<?php

namespace dao;

use dao\DaoFactory;
use PDO;
use entity\BaseEntity;

class BaseDao
{

    protected $tableName;

    private $pdo;
    public function __construct() 
    {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() 
    {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function selectById(int $id)
    {
        $sql = "select * from {$this->tableName}
        where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function insert(BaseEntity $entity)
    {
        $sql = "insert into {$this->tableName} (";
        $i = 0;
        foreach($entity as $key => $value) {
            if ($key === 'id' || $key === 'register_date' || $value === null) {
                continue;
            }
            if($i !== 0) {
                $sql .= ", ";    
            }
            $sql .= $this->underscore($key);
            $i++;
        }
        $sql .= ", register_date) values (";
        $i = 0;
        foreach($entity as $key => $value) {
            if ($key === 'id' || $key === 'register_date' || $value === null) {
                continue;
            }
            if($i !== 0) {
                $sql .= ", ";    
            }
            $sql .= ":" . $key;
            $i++;
        }
        $sql .= ", :register_date)";

        $prepare = $this->pdo->prepare($sql);
        foreach($entity as $key => $value) {
            if ($value === null) {
                continue;
            }
            $prepare->bindValue($key, $value);
        }
        // delete_flgはテーブルの制約でデフォルト値1が入る想定
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(BaseEntity $entity)
    {
        $sql = "update {$this->tableName} set ";
        $i = 0;
        foreach($entity as $key => $value) {
            if ($key === 'id' || $key === 'registerDate' || $key === 'updateDate') {
                continue;
            }
            if($i !== 0) {
                $sql .= ", ";    
            }
            $sql .= $this->underscore($key) . " = :" . $key;
            $i++;
        }
        $sql .= " , update_date = :updateDate
        where id = :id";

        $prepare = $this->pdo->prepare($sql);
        foreach($entity as $key => $value) {
            if ($key === 'id' || $key === 'registerDate' || $key === 'updateDate') {
                continue;
            }
            $prepare->bindValue($key, $value);
        }
        $prepare->bindValue(':updateDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $entity->id, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function delete(int $id)
    {
        $sql = "delete from {$this->tableName} where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 削除フラグの更新
    public function updateDeleteFlg(int $id) {
        $sql = "update {$this->tableName} set 
        delete_flg = 9 
        , update_date = :update_date
        where id = :id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    private static function underscore($str)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
}