<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\Companion;

class CompanionDao {

    public function getCompanionList(int $participantId) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from companion where participant_id = :participant_id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function insert(Companion $companion) {
        $pdo = DaoFactory::getConnection();
        $sql = 'insert into companion (participant_id, occupation, sex, name) 
                values (:participant_id, :occupation, :sex, :name)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $companion->participantId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $companion->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $companion->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $companion->name, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function deleteByparticipantId(int $participantId) {
        $pdo = DaoFactory::getConnection();
        $sql = 'delete from companion where participant_id = :participant_id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function update(Companion $companion) {
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