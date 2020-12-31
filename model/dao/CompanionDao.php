<?php

namespace dao;

use dao\DaoFactory;
use PDO;
use entity\Companion;

class CompanionDao 
{

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

    public function getCompanionList(int $participantId) 
    {
        $sql = 'select * from companion 
                where participant_id = :participant_id
                order by id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function insert(Companion $companion) 
    {
        $sql = 'insert into companion 
        (participant_id
        , occupation
        , sex
        , name
        , register_date
        , amount) 
            values (
            :participant_id
            , :occupation
            , :sex
            , :name
            , :register_date
            , :amount)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $companion->participantId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $companion->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $companion->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $companion->name, PDO::PARAM_STR);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':amount', $companion->amount, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function deleteByparticipantId(int $participantId) 
    {
        $sql = 'delete from companion where participant_id = :participant_id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
    }

}

?>