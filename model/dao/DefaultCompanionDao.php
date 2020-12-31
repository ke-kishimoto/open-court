<?php
namespace dao;

use PDO;

class DefaultCompanionDao extends BaseDao
{

    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'default_companion';
    }
    
    public function getDefaultCompanionList(int $userId) 
    {
        $sql = 'select * from default_companion where user_id = :user_id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function deleteByuserId(int $userId) 
    {
        $sql = 'delete from default_companion where user_id = :user_id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $prepare->execute();
    }

}

?>