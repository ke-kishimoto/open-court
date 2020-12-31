<?php

namespace dao;

use PDO;

require_once(__DIR__.'/BaseDao.php');

class CompanionDao extends BaseDao
{

    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'companion';
    }
    

    public function getCompanionList(int $participantId) 
    {
        $sql = 'select * from companion 
                where participant_id = :participant_id
                order by id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function deleteByparticipantId(int $participantId) 
    {
        $sql = 'delete from companion where participant_id = :participant_id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':participant_id', $participantId, PDO::PARAM_INT);
        $prepare->execute();
    }

}

?>