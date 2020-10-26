<?php
namespace dao;

use dao\DaoFactory;
use PDO;
use entity\DefaultCompanion;

class DefaultCompanionDao {

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

    public function getDefaultCompanionList(int $userId) {
        $sql = 'select * from default_companion where user_id = :user_id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function insert(DefaultCompanion $defaultCompanion) {
        $sql = 'insert into default_companion (user_id, occupation, sex, name, register_date) 
                values (:user_id, :occupation, :sex, :name, :register_date)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':user_id', $defaultCompanion->userId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $defaultCompanion->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $defaultCompanion->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $defaultCompanion->name, PDO::PARAM_STR);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function deleteByuserId(int $userId) {
        $sql = 'delete from default_companion where user_id = :user_id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function update(DefaultCompanion $defaultCompanion) {
        // $pdo = DaoFactory::getConnection();
        // $sql = 'update config set 
        // line_token = :line_token
        // where id = :id';
        // $prepare = $pdo->prepare($sql);
        // $prepare->bindValue(':id', $config->id, PDO::PARAM_INT);
        // $prepare->bindValue(':line_token', $config->lineToken, PDO::PARAM_STR);
        // $prepare->execute();
    }
}

?>